<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Updates_CacheCollateFields
 * 
 * This class manages the step process of running through the CacheCollateFields required update.
 * It will define an object to pull data from (if necessary) to pick back up if exited early.
 * It will run an upgrade function to alter the nf3_fields and nf3_field_meta tables.
 * Then, it will step over each form on the site, following this process:
 * - Fields that exist in the data tables but not in the cache will be deleted.
 * - Fields that exist in the cache but not in the data tables will be inserted.
 * - Fields that exist in the data tables but have an incorrect form ID will be inserted as a new ID and referenced from the cache.
 * - Fields that exist in both will be updated from the cache to ensure the data is correct.
 * After completing the above for every form on the site, it will remove the data object that manages its location.
 */
class NF_Updates_CacheFieldReconcilliation extends NF_Abstracts_RequiredUpdate
{
    
    private $data = array();
    
    private $running = array();
    
    /**
     * Non-associatve array of field ids from the cache.
     * @var array
     */
    private $field_ids = array();

    /**
     * columns to retrieve from meta table
     */
    private $meta_keys = array(
        'label',
        'key',
        'order',
        'required',
        'default',
        'label_pos',
        'personally_identifiable'
    );

    /**
     * The denominator object for calculating our steps.
     * @var Integer
     */
    private $divisor = 200;

    /**
     * Hard limit for the number of querys we run during a single step.
     * @var integer
     */
    private $limit = 10;
    
    /**
     * The table names for our database queries.
     */
    private $table;
    private $meta_table;

    /**
     * Constructor
     * 
     * @param $data (Array) The data object passed in by the AJAX call.
     * @param $running (Array) The array of required updates being run.
     * 
     * @since 3.4.0
     */
    public function __construct( $data = array(), $running )
    {
        // Build our arguments array.
        $args = array(
            'slug' => 'CacheFieldReconcilliation',
            'class_name' => 'NF_Updates_CacheFieldReconcilliation',
            'debug' => false,
        );
        $this->data = $data;
        $this->running = $running;

        // Call the parent constructor.
        parent::__construct( $args );
        
        // Set our table names.
        $this->table = $this->db->prefix . 'nf3_fields';
        $this->meta_table = $this->db->prefix . 'nf3_field_meta';

        // Begin processing.
        $this->process();
    }

    /**
     * Function to loop over the batch.
     * 
     * @since 3.4.0
     */
    public function process()
    {
        // If we've not already started...
        if ( ! isset( $this->running[ 0 ][ 'running' ] ) ) {
            // Run our startup method.
            $this->startup();
        }
        
        /**
         * Get all of our database variables up and running.
         * Sets up class vars that are used in subsequent methods.
         */
        $this->setup_vars();

        /**
         * Get the next round of fields to update
         */
        $this->get_fields_this_step();

        /**
         * Update fields
         */
        $this->update_fields();

        /**
         * Saves our current location, along with any processing data we may need for the next step.
         * If we're done with our step, runs cleanup instead.
         */
        $this->end_of_step();

        /**
         * Respond to the AJAX call.
         */
        $this->respond();
    }



    /**
     * Function to run any setup steps necessary to begin processing.
     * 
     * @since 3.4.0
     */
    public function startup()
    {
        // Record that we're processing the update.
        $this->running[ 0 ][ 'running' ] = true;
        
        $sql = "SELECT ID FROM `{$this->table}`";
        $fields = $this->db->get_results( $sql, 'ARRAY_A' );
        // Record the total number of steps in this batch.
        $this->running[ 0 ][ 'steps' ] = ceil(count( $fields ) / $this->divisor);
        // Record our current step (defaulted to 0 here).
        $this->running[ 0 ][ 'current' ] = 0;
    }

    public function get_fields_this_step() {

        $offset = 0;

        if( 0 < $this->running[ 0 ][ 'current' ] ) {
            $offset = $this->running[ 0 ][ 'current' ] * $this->divisor;
        }

        // Get a list of our forms...
        $sql = "SELECT ID FROM `{$this->table}` LIMIT {$offset}, {$this->divisor}";
        $this->field_ids = $this->db->get_results( $sql, 'ARRAY_A' );
        $this->field_ids = $this->array_squash( $this->field_ids );
        // $this->running[ 0 ][ 'fields' ] = $this->field_ids;
    }

    /**
     * Update field table records with data from field meta
     */
    public function update_fields() {
        $field_meta = $this->get_field_meta();

        if($field_meta) {
            $update_query = $this->get_update_query( $field_meta );

            if( $update_query ) {
                $this->db->query($update_query);
            }
        }
    }

    /**
     * Get meta data to use for updating 
     */
    public function get_field_meta() {

        if(0 === count($this->field_ids)) return false;

        $in_fields = implode( ', ', $this->field_ids );
        $meta_keys = "'" . implode( "' , '", $this->meta_keys ) . "'";

        $meta_query = "SELECT `parent_id`, `key`, `meta_key`, `meta_value`, `value` FROM `{$this->meta_table}` WHERE `parent_id` IN ({$in_fields}) AND `key` IN ({$meta_keys}) ORDER BY `parent_id` ASC";

        $results = $this->db->get_results( $meta_query, 'ARRAY_A');

        $meta_data = array();

        foreach( $results as $meta ) {
            $parent_id = $meta['parent_id'];
            foreach( $meta as $key => $val ) {

                if( 'parent_id' !== $key ) {
                    $meta_data[ $parent_id ][ $meta['key'] ] = $meta['value'];
                    $meta_data[ $parent_id ][ 'meta_' . $meta['meta_key'] ] = $meta['meta_value'];
                }
            }
        }

        return $meta_data;
    }

    /**
     * Construct field update query
     */
    public function get_update_query( $field_data ) {
        if( 0 === count( $field_data) ) return false;

        $sql = "INSERT INTO {$this->table} 
        (`id`, `label`, `key`, `field_label`, `field_key`, `order`, `required`, `default_value`, `label_pos`, `personally_identifiable`)
        VALUES";

        foreach( $field_data as $field_id => $meta ) {
            $sql .= "({$field_id}, '{$this->db->_real_escape($meta['label'])}', '{$this->db->_real_escape($meta['key'])}', '{$this->db->_real_escape($meta['meta_label'])}', '{$this->db->_real_escape($meta['meta_key'])}', {$meta['order']},";
            
            if( isset( $meta[ 'required' ] ) && '' !== $meta[ 'required' ]) {
                $sql .= "{$meta['required']},";
             } else {
                 $sql .= "0,";
             } 
             
             if(isset( $meta[ 'meta_default' ] ) ) {
                 $sql .= "'{$this->db->_real_escape($meta['meta_default'])}',";
              } else {
                  $sql .= "'',";
              } 
              
              if( isset( $meta[ 'meta_label_pos' ] ) ) {
                  $sql .= "'{$meta['meta_label_pos']}',";
              } else {
                  $sql .= "'',";
              }
            
            if(isset($meta['personally_identifiable'])) {
                $sql .= "{$meta['personally_identifiable']}";
            } else {
                $sql .= "0";
            }

            $sql .= "),";
        }

        $sql = rtrim( $sql, ',' );

        $sql .= "ON DUPLICATE KEY
            UPDATE
            `label` = VALUES(`label`),
            `key` = VALUES(`key`),
            `field_label` = VALUES(`field_label`),
            `field_key` = VALUES(`field_key`),
            `order` = VALUES(`order`),
            `required` = VALUES(`required`),
            `required` = VALUES(`required`),
            `default_value` = VALUES(`default_value`),
            `label_pos` = VALUES(`label_pos`),
            `personally_identifiable` = VALUES(`personally_identifiable`)";

        return $sql;
    }

    /**
     * Function to cleanup any lingering temporary elements of a required update after completion.
     * 
     * @since 3.4.0
     */
    public function cleanup()
    {
        // Remove the current process from the array.
        array_shift( $this->running );
        // Record to our updates setting that this update is complete.
        $this->confirm_complete();
        // If we have no updates left to process...
        if ( empty( $this->running ) ) {
            // Call the parent cleanup method.
            parent::cleanup();
        }
    }

    /**
     * Most of the methods in this class use class vars to access and store data.
     *
     * This method sets the initial state of these class vars.
     * Class vars include:
     *    $field_ids <- non-associatve array of field ids from the database.
     *
     * If we are not running a form for the first time, 
     * we set class vars based on what we have been passed. 
     * After setting those class vars, we bail early.
     * 
     * If we are running for the first time, set have to hit the database to
     * get the information for class vars.
     * This method doesn't perform those operations, but it sets the class vars that the appropriate
     * methods use to figure out what to add and remove.
     *
     * @since  3.4.0
     * @return void
     */
    private function setup_vars()
    {
        // Enable maintenance mode on the front end when the fields start processing.
        // $this->enable_maintenance_mode( $this->db->prefix, $this->form[ 'ID' ] );       
    }

    /**
     * After we've done our processing, but before we get to step cleanup, we need to store process information.
     *
     * This method updates our form class var so that it can be passed to the next step.
     * If we've completed this step, it calls the cleanup method.
     * 
     * @since  3.4.0
     * @return void
     */
    private function end_of_step()
    {
        $this->running[ 0 ][ 'current' ] = intval( $this->running[ 0 ][ 'current' ] ) + 1;

        // Prepare to output our number of steps and current step.
        $this->response[ 'stepsTotal' ] = $this->running[ 0 ][ 'steps' ];
        $this->response[ 'currentStep' ] = $this->running[ 0 ][ 'current' ];
        
        if ( $this->divisor > count($this->field_ids)) {
            // Run our cleanup method.
            $this->cleanup();
        }
        
        // Record our current location in the process.
        update_option( 'ninja_forms_doing_required_updates', $this->running );
        // Prepare to output the number of updates remaining.
        $this->response[ 'updatesRemaining' ] = count( $this->running );
    }

    /**
    * Function to compress our db results into a more useful format.
    * 
    * @param $data (Array) The result to be compressed.
    * 
    * @return (Array) Associative if our data was complex.
    *                 Non-associative if our data was a single item.
    * 
    * @since UPDATE_VERSION_ON_MERGE
    */
    private function array_squash( $data )
    {
        $response = array();
        // For each item in the array...
        foreach ( $data as $row ) {
            // If the item has more than 1 attribute...
            if ( 1 < count( $row ) ) {
                // Assign the data to an associated result.
                $response[] = intval($row['ID']);
                // Unset the id setting, as that will be the key.
                unset( $response[ $row[ 'ID' ] ][ '' ] );
            } // Otherwise... (We only have 1 attribute.)
            else {
                // Add the id to the stack in a non-associated result.
                $response[] = intval( $row[ 'ID' ] );
            }
        }
        return $response;
    }
}