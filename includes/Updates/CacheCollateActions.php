<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_RequiredUpdate
 */
class NF_Updates_CacheCollateActions extends NF_Abstracts_RequiredUpdate
{
    
    private $data = array();
    
    private $running = array();
    
    private $db;

    /**
     * Constructor
     */
    public function __construct( $data = array(), $running )
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->_slug = 'CacheCollateActions';
        $this->_class_name = 'NF_Updates_CacheCollateActions';
        $this->data = $data;
        $this->running = $running;
        parent::__construct();
        // Begin processing.
        $this->process();
    }


    /**
     * Function to loop over the batch.
     */
    public function process()
    {
        // If we've not already started...
        if ( ! isset( $this->running[ 0 ][ 'running' ] ) ) {
            // Run our startup method.
            $this->startup();
        }
        
        // See which form we're currently working with.
        $form = array_pop( $this->running[ 0 ][ 'forms' ] );
        
        // Get the actions for that form.
        $actions = Ninja_Forms()->form( $form[ 'ID' ] )->get_actions();
        $action_ids = array();
        $actions_by_id = array();
        
        // Setup variables for our SQL methods.
        $insert = array();
        $delete = array();
        
        // For each action...
        foreach ( $actions as $action ) {
            // Add the ID to the list.
            array_push( $action_ids, $action->get_id() );
            $actions_by_id[ $action->get_id() ] = $action->get_settings();
        }
        // Cross reference the Actions table to see if these IDs exist for this Form.
        $sql = "SELECT id FROM `{$this->db->prefix}nf3_actions` WHERE id IN(" . implode( ', ', $action_ids ) . ") AND parent_id = {$form[ 'ID' ]}";
        $db_actions = $this->db->get_results( $sql, 'ARRAY_A' );
        $db_action_ids = array();
        // For each action in the actions table...
        foreach ( $db_actions as $action ) {
            // If we have no reference to it in the cache...
            if ( ! in_array( $action[ 'id' ], $action_ids ) ) {
                // Schedule it for deletion.
                array_push( $delete, $action[ 'id' ] );
            }
            // Push the id onto our comparison array.
            array_push( $db_action_ids, $action[ 'id' ] );
        }
        // For each action in the cache...
        foreach ( $action_ids as $action ) {
            // If we have no reference to it in the actions table...
            if ( ! in_array( $action, $db_action_ids ) ) {
                // Schedule it for insertion.
                array_push( $insert, $action );
            }
        }
        
        // Cross reference the Actions table to see if these IDs exist on other Forms.
        $sql = "SELECT id FROM `{$this->db->prefix}nf3_actions` WHERE id IN(" . implode( ', ', $action_ids ) . ") AND parent_id <> {$form[ 'ID' ]}";
        $duplicates = $this->db->get_results( $sql, 'ARRAY_A' );
        // If we got something back...
        // (There were duplicates.)
        if ( ! empty( $duplicates ) ) {
            // For each duplicate...
            foreach ( $duplicates as $duplicate ) {
                // Schedule it for insertion.
                array_push( $insert, $duplicate[ 'id' ] );
            }
        }
        
        // At this point, we should have a list of actions to insert and delete.
        // Any remaining actions beyond those will be updated.
        echo('Action IDs<br />');
        var_dump($action_ids);
        echo('<br />Delete<br />');
        var_dump($delete);
        echo('<br />Insert<br />');
        var_dump($insert);
        die();

    }


    /**
     * Function to run any setup steps necessary to begin processing.
     */
    public function startup()
    {
        // Record that we're processing the update.
        $this->running[ 0 ][ 'running' ] = true;
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
     * Function to cleanup any lingering temporary elements of a batch process after completion.
     */
    public function cleanup()
    {
        /**
         * This function intentionally left empty.
         */
    }
}