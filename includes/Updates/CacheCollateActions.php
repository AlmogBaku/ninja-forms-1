<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_RequiredUpdate
 */
class NF_Updates_CacheCollateActions extends NF_Abstracts_RequiredUpdate
{
    
    private $data = array();
    
    private $running = array();
    
    private $lock_process = false;
    
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
        
        // Setup variables for our SQL methods.
        $action_ids = array();
        $actions_by_id = array();
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
        
        // If we're not continuing an old process...
        if ( ! isset( $form[ 'action_ids' ] ) ) {
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
        } // Otherwise... (We are continuing.)
        else {
            $action_ids = $form[ 'action_ids' ];
            $insert = $form[ 'insert' ];
        }
        // Garbage collection.
        unset( $db_actions );
        unset( $db_action_ids );

//        echo('Action IDs<br />');
//        var_dump($action_ids);
//        echo('<br />Delete<br />');
//        var_dump($delete);
//        echo('<br />Insert<br />');
//        var_dump($insert);
//        var_dump( $actions_by_id[ '43' ] );
        // TODO: Remove this die statement when ready for testing.
        die();
        
        // If we have items to delete...
        if ( ! empty( $delete ) ) {
            // Delete all meta for those actions.
            $sql = "DELETE FROM `{$this->db->prefix}nf3_action_meta` WHERE parent_id IN(" . implode( ', ', $delete ) . ")";
            $this->db->query( $sql );
            // Delete the actions.
            $sql = "DELETE FROM `{$this->db->prefix}nf3_actions` WHERE id IN(" . implode( ', ', $delete ) . ")";
            $this->db->query( $sql );
            // Empty out the delete list.
            $delete = array();
        }
        
        // If we have items to insert...
        if ( ! empty( $insert ) ) {
            // Set our hard limit for the loop.
            $limit = 10;
            // Store the meta items outside the loop for faster insertion.
            $meta_items = array();
            // While we still have items to insert...
            while ( 0 < count( $insert ) ) {
                // If we have hit our limit...
                if ( 1 > $limit ) {
                    // Lock processing.
                    $this->lock_process = true;
                    // Exit the loop.
                    break;
                }
                // Get our item to be inserted.
                $inserting = array_pop( $insert );
                $settings = $actions_by_id[ $inserting ];
                // Insert into the actions table.
                $sql = "INSERT INTO `{$this->db->prefix}nf3_actions` ( type, active, parent_id, created_at ) VALUES ( '" . $settings[ 'type' ] . "', " . intval( $settings[ 'active' ] ) . ", " . intval( $form[ 'ID' ] ) . ", '" . $settings[ 'created_at' ] . "' )";
                $this->db->query( $sql );
                // Get the ID of the new action.
                $new_id = $this->db->insert_id;
                // For each meta of the action...
                foreach ( $settings as $meta => $value ) {
                    // If it's not empty...
                    if ( ( ! empty( $value ) || '0' == $value ) ) {
                        // Add the data to the list.
                        array_push( $meta_items, "( " . intval( $new_id ) . ", '" . $meta . "', '" . $this->prepare( $value ) . "' )" );
                    }
                }
                // Remove the item from the list of actions.
                unset( $actions_by_id[ $inserting ] );
                // Reduce the limit.
                $limit--;
            }
            // Insert our meta.
            $sql = "INSERT INTO `{$this->db->prefix}nf3_action_meta` ( parent_id, `key`, value ) VALUES " . implode( ', ', $meta_items );
            $this->db->query( $sql );
        }
        
        // At this point, we should only have items to update.
        
        // If we have items left to process...
        // AND If processing hasn't been locked...
        if ( ! empty( $action_ids ) && ! $this->lock_process ) {
            // Set our hard limit for the loop.
            $limit = 10;
            // Store the meta items outside the loop for faster insertion.
            $meta_items = array();
            $flush_ids = array();
            // While we still have items to update...
            while ( 0 < count( $action_ids ) ) {
                // If we have hit our limit...
                if ( 1 > $limit ) {
                    // Lock processing.
                    $this->lock_process = true;
                    // Exit the loop.
                    break;
                }
                // Get our item to be updated.
                $updating = array_pop( $action_ids );
                array_push( $flush_ids, $updating );
                $settings = $actions_by_id[ $updating ];
                // Update the actions table.
                $sql = "UPDATE `{$this->db->prefix}nf3_actions` SET type = '" . $settings[ 'type' ] . "', active = " . intval( $settings[ 'active' ] ) . ", created_at = '" . $settings[ 'created_at' ] . "' WHERE id = " . intval( $updating );
                $this->db->query( $sql );
                // For each meta of the action...
                foreach ( $settings as $meta => $value ) {
                    // If it's not empty...
                    if ( ( ! empty( $value ) || '0' == $value ) ) {
                        // Add the data to the list.
                        array_push( $meta_items, "( " . intval( $new_id ) . ", '" . $meta . "', '" . $this->prepare( $value ) . "' )" );
                    }
                }
                // Remove the item from the list of actions.
                unset( $actions_by_id[ $updating ] );
                // Reduce the limit.
                $limit--;
            }
            // Flush our existing meta.
            $sql = "DELETE FROM `{$this->db->prefix}nf3_action_meta` WHERE parent_id IN(" . implode( ', ', $flush_ids ) . ")";
            $this->db->query( $sql );
            // Insert our updated meta.
            $sql = "INSERT INTO `{$this->db->prefix}nf3_action_meta` ( parent_id, `key`, value ) VALUES " . implode( ', ', $meta_items );
            $this->db->query( $sql );
        }
        
        // If we have locked processing...
        if ( $this->lock_process ) {
            // Reset the action_ids array.
            $action_ids = array();
            // For each action left to process...
            foreach ( $actions_by_id as $id => $action ) {
                // If we've not already processed this action...
                if ( in_array( $id, $form[ 'action_ids' ] ) ) {
                    // Save a reference to its ID.
                    array_push( $action_ids, $id );
                }
            }
            // Store our current data location.
            $form[ 'insert' ] = $insert;
            $form[ 'action_ids' ] = $action_ids;
            array_push( $this->running[ 0 ][ 'forms' ], $form );
        } // Otherwise... (The step is complete.)
        else {
            // If all steps are completed...
            if ( empty( $this->running[ 0 ][ 'forms' ] ) ) {
                // Run our cleanup process.
                $this->cleanup();
            } // Otherwise... (We still have steps to process.)
            else {
                // Increment our step count.
                $this->running[ 0 ][ 'current' ] = intval( $this->running[ 0 ][ 'current' ] + 1 );
            }
        }
        // Update the cache.
        // Dump our response.

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
        // Record our current step (defaulted to 1 here).
        $this->running[ 0 ][ 'current' ] = 1;
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


    /**
     * Function to prepare our query values for insert.
     */
    public function prepare( $value )
    {
        $escaped = $this->db->escape_by_ref( $value );
        // Serialize the value if necessary.
        $escaped = maybe_serialize( $escaped );

        return $escaped;
    }
}