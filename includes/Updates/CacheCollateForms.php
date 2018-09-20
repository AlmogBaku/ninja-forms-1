<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Updates_CacheCollateForms
 * 
 * This class manages the step process of running through the CacheCollateForms required update.
 * It will define an object to pull data from (if necessary) to pick back up if exited early.
 * It will run an upgrade function to alter the nf3_forms and nf3_form_meta tables.
 * Then, it will step over each form on the site, following this process:
 * - New columns in the nf3_forms table will be populated with data from the cache.
 * - New and existing columns in the nf3_form_meta tables will be populated from the cache.
 * - A new record of the cache will be saved to the nf3_upgrades table (if it does not already exist).
 * After completing the above for every form on the site, it will remove the data object that manages its location.
 */
class NF_Updates_CacheCollateForms extends NF_Abstracts_RequiredUpdate
{
    
    private $data = array();
    
    private $running = array();
    
    private $lock_process = false;
    
    private $db;

    /**
     * Stores information about the current form being processed.
     * @var array
     */
    private $form;

    /**
     * Declare a blacklist for settings to not be inserted.
     * @var array
     */
    private $blacklist = array(
            'title',
            'objectType',
            'editActive',
        );

    /**
     * Constructor
     * 
     * @param $data (Array) The data object passed in by the AJAX call.
     * @param $running (Array) The array of required updates being run.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function __construct( $data = array(), $running )
    {
        // Save a reference to wpdb.
        global $wpdb;
        $this->db = $wpdb;

        // Set debug for testing or live transactions.
        $this->debug = false;

        // Define the class variables.
        $this->_slug = 'CacheCollateForms';
        $this->_class_name = 'NF_Updates_CacheCollateForms';
        $this->data = $data;
        $this->running = $running;

        // Call the parent constructor.
        parent::__construct();

        // Begin processing.
        $this->process();
    }


    /**
     * Function to loop over the batch.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function process()
    {
        // If we've not already started...
        if ( ! isset( $this->running[ 0 ][ 'running' ] ) ) {
            // Run our startup method.
            $this->startup();
        }

        // See which form we're currently working with.
        $this->form = array_pop( $this->running[ 0 ][ 'forms' ] );
        
        /**
         * Update our form table with the appropriate form settings.
         */
        $this->update_form();
        
        /**
         * Check to see if we're done with processing this form and prepare to respond.
         */
        $this->end_of_step();

        // Respond to the AJAX call.
        $this->respond();
    }


    /**
     * Function to run any setup steps necessary to begin processing.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function startup()
    {
        // Record that we're processing the update.
        $this->running[ 0 ][ 'running' ] = true;
        // If we're not debugging...
        if ( ! $this->debug ) {
            // Ensure that our data tables are updated.
            $this->migrate();
        }
        // Get a list of our forms...
        $sql = "SELECT ID FROM `{$this->db->prefix}nf3_forms`";
        $forms = $this->db->get_results( $sql, 'ARRAY_A' );
        $this->running[ 0 ][ 'forms' ] = $forms;
        // Record the total number of steps in this batch.
        $this->running[ 0 ][ 'steps' ] = count( $forms );
        // Record our current step (defaulted to 0 here).
        $this->running[ 0 ][ 'current' ] = 0;
    }


    /**
     * Function to cleanup any lingering temporary elements of a required update after completion.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function cleanup()
    {
        // Remove the current process from the array.
        array_shift( $this->running );
        // Record to our updates setting that this update is complete.
        $updates = get_option( 'ninja_forms_required_updates', array() );
        $updates[ $this->_slug ] = 'complete';
        update_option( 'ninja_forms_required_updates', $updates );
        // If we have no updates left to process...
        if ( empty( $this->running ) ) {
            // Call the parent cleanup method.
            parent::cleanup();
        }
    }


    /**
     * Function to prepare our query values for insert.
     * 
     * @param $value (Mixed) The value to be escaped for SQL.
     * @return (String) The escaped (and possibly serialized) value of the string.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function prepare( $value )
    {
        // If our value is a number...
        if ( is_float( $value ) ) {
            // Exit early and return the value.
            return $value;
        }
        // Serialize the value if necessary.
        $escaped = maybe_serialize( $value );
        // Escape it.
        $escaped = $this->db->_real_escape( $escaped );

        return $escaped;
    }


    /**
     * Function used to call queries that are gated by debug.
     * 
     * @param $sql (String) The query to be run.
     * @return (Object) The response to the wpdb query call.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function query( $sql )
    {
        // If we're not debugging...
        if ( ! $this->debug ) {
            // Run the query.
            return $this->db->query( $sql );
        }
        // Otherwise, return false.
        return false;
    }


    /**
     * Function to run our table migrations.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function migrate()
    {
        $migrations = new NF_Database_Migrations();
        $migrations->do_upgrade( 'cache_collate_forms' );
    }

    /**
     * 
     * @since  UPDATE_VERSION_ON_MERGE
     * @return void
     */
    private function end_of_step()
    {
        // Update the upgrades table, passing in 1 for the current stage.
        $cache = WPN_Helper::get_nf_cache( $this->form[ 'ID' ] );
        WPN_Helper::update_nf_cache( $this->form[ 'ID' ], $cache, 1 );

        // Increment our step count.
        $this->running[ 0 ][ 'current' ] = intval( $this->running[ 0 ][ 'current' ] ) + 1;

        // Prepare to output our number of steps and current step.
        $this->response[ 'stepsTotal' ] = $this->running[ 0 ][ 'steps' ];
        $this->response[ 'currentStep' ] = $this->running[ 0 ][ 'current' ];

        // If all steps have been completed...
        if ( empty( $this->running[ 0 ][ 'forms' ] ) ) {
            // Run our cleanup method.
            $this->cleanup();
        }

        // Record our current location in the process.
        update_option( 'ninja_forms_doing_required_updates', $this->running );
        // Prepare to output the number of updates remaining.
        $this->response[ 'updatesRemaining' ] = count( $this->running );
    }

    /**
     * Update our form table for the current form.
     * We have new table columns, so we want to make sure that those are populated properly.
     *
     * Also checks meta values against our $this->blacklist.
     * 
     * @since  UPDATE_VERSION_ON_MERGE
     * @return [type]  [description]
     */
    private function update_form()
    {
        // Get the settings for that form.
        $settings = Ninja_Forms()->form( $this->form[ 'ID' ] )->get()->get_settings();
        
        // Get our seq_number from meta.
        $sql = "SELECT `value` FROM `{$this->db->prefix}nf3_form_meta` WHERE `key` = '_seq_num' AND `parent_id` = " . intval( $this->form[ 'ID' ] );
        $result = $this->db->query( $sql, 'ARRAY_A' );
        // Default to 1.
        $seq_num = 1;
        if ( ! empty( $result[ 0 ][ 'value' ] ) ) {
            // If we got back something, set it to the proper value.
            $seq_num = intval( $result[ 0 ][ 'value' ] );
        }
        
        // If logged in is false...
        if ( ! $settings[ 'logged_in' ] || 'false' === $settings[ 'logged_in' ] ) {
            $logged_in = 0;
        } // Otherwise... (logged in is true.)
        else {
            $logged_in = 1;
        }
        
        // Save the new columns to the forms table.
        $sql = "UPDATE `{$this->db->prefix}nf3_forms` SET form_title = '" . $this->prepare( $settings[ 'title' ] ) . "', default_label_pos = '" . $settings[ 'default_label_pos' ] . "', show_title = " . intval( $settings[ 'show_title' ] ) . ", clear_complete = " . intval( $settings[ 'clear_complete' ] ) . ", hide_complete = " . intval( $settings[ 'hide_complete' ] ) . ", logged_in = {$logged_in}, seq_num = {$seq_num} WHERE id = " . intval( $this->form[ 'ID' ] ) . ";";
        $this->query( $sql );
        
        // Remove the existing meta from the form_meta table.
        $sql = "DELETE FROM `{$this->db->prefix}nf3_form_meta` WHERE parent_id = " . intval( $this->form[ 'ID' ] );
        $this->query( $sql );
                
        $insert_items = array();
        // Add _seq_num since it's protected and won't be a setting.
        array_push( $insert_items, "( " . intval( $this->form[ 'ID' ] ) . ", '_seq_num', '{$seq_num}', '_seq_num', '{$seq_num}' )" );
        // For each form setting...
        foreach ( $settings as $key => $setting ) {
            // If it's not a restricted setting...
            if ( ! in_array( $key, $this->blacklist ) ) {
                // Add it to the stack.
                array_push( $insert_items, "( " . intval( $this->form[ 'ID' ] ) . ", '{$key}', '" . $this->prepare( $setting ) . "', '{$key}', '" . $this->prepare( $setting ) . "'  )" );
            }
        }
        // Insert the new meta values.
        $sql = "INSERT INTO `{$this->db->prefix}nf3_form_meta` ( parent_id, `key`, `value`, meta_key, meta_value ) VALUES " . implode( ', ', $insert_items );
        $this->query( $sql );
    }

}