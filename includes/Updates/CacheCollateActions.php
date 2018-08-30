<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Updates_CacheCollateActions
 * 
 * This class manages the step process of running through the CacheCollateActions required update.
 * It will define an object to pull data from (if necessary) to pick back up if exited early.
 * It will run an upgrade function to alter the nf3_actions and nf3_action_meta tables.
 * Then, it will step over each form on the site, following this process:
 * - Actions that exist in the data tables but not in the cache will be deleted.
 * - Actions that exist in the cache but not in the data tables will be inserted.
 * - Actions that exist in the data tables but have an incorrect form ID will be inserted as a new ID and referenced from the cache.
 * - Actions that exist in both will be updated from the cache to ensure the data is correct.
 * After completing the above for every form on the site, it will remove the data object that manages its location.
 */
class NF_Updates_CacheCollateActions extends NF_Abstracts_RequiredUpdate
{
    
    private $data = array();
    
    private $running = array();
    
    private $lock_process = false;
    
    private $db;

    /**
     * Constructor
     * 
     * @param $data (Array) The data object passed in by the AJAX call.
     * @param $running (Array) The array of required updates being run.
     */
    public function __construct( $data = array(), $running )
    {
        // Save a reference to wpdb.
        global $wpdb;
        $this->db = $wpdb;

        // Set debug for testing or live transactions.
        $this->debug = true;

        // Define the class variables.
        $this->_slug = 'CacheCollateActions';
        $this->_class_name = 'NF_Updates_CacheCollateActions';
        $this->data = $data;
        $this->running = $running;

        // Call the parent constructor.
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
        
        // If we have items to delete...
        if ( ! empty( $delete ) ) {
            // Run our deletion process.
            $this->delete( $delete );
            // Empty out the delete list.
            $delete = array();
        }

        // Set our hard limit for the loops.
        $limit = 10;

        // Setup a holding object for inserted items.
        $insert_ids = array();

        // If we have items to insert...
        if ( ! empty( $insert ) ) {
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
                $sql = "INSERT INTO `{$this->db->prefix}nf3_actions` ( type, active, parent_id, created_at, label ) VALUES ( '" . $settings[ 'type' ] . "', " . intval( $settings[ 'active' ] ) . ", " . intval( $form[ 'ID' ] ) . ", '" . $settings[ 'created_at' ] . "', '" . $this->prepare( $settings[ 'label' ] ) . "' )";
                $this->query( $sql );
                // Set a default new_id for debugging.
                $new_id = 0;
                // If we're not in debug mode...
                if ( ! $this->debug ) {
                    // Get the ID of the new action.
                    $new_id = $this->db->insert_id;
                }
                // Save a reference to this insertion.
                $insert_ids[ $inserting ] = $new_id;
                // For each meta of the action...
                foreach ( $settings as $meta => $value ) {
                    // If it's not empty...
                    if ( ( ! empty( $value ) || '0' == $value ) ) {
                        // Add the data to the list.
                        array_push( $meta_items, "( " . intval( $new_id ) . ", '" . $meta . "', '" . $this->prepare( $value ) . "', '" . $meta . "', '" . $this->prepare( $value ) . "' )" );
                    }
                }
                // Remove the item from the list of actions.
                unset( $actions_by_id[ $inserting ] );
                // Reduce the limit.
                $limit--;
            }
            // Insert our meta.
            $sql = "INSERT INTO `{$this->db->prefix}nf3_action_meta` ( parent_id, `key`, value, meta_key, meta_value ) VALUES " . implode( ', ', $meta_items );
            $this->query( $sql );
        }
        
        // At this point, we should only have items to update.
        
        // If we have items left to process...
        // AND If processing hasn't been locked...
        if ( ! empty( $action_ids ) && ! $this->lock_process ) {
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
                $sql = "UPDATE `{$this->db->prefix}nf3_actions` SET type = '" . $settings[ 'type' ] . "', active = " . intval( $settings[ 'active' ] ) . ", created_at = '" . $settings[ 'created_at' ] . "', label = '" . $this->prepare( $settings[ 'label' ] ) . "' WHERE id = " . intval( $updating );
                $this->query( $sql );
                // For each meta of the action...
                foreach ( $settings as $meta => $value ) {
                    // If it's not empty...
                    if ( ( ! empty( $value ) || '0' == $value ) ) {
                        // Add the data to the list.
                        array_push( $meta_items, "( " . intval( $new_id ) . ", '" . $meta . "', '" . $this->prepare( $value ) . "', '" . $meta . "', '" . $this->prepare( $value ) . "' )" );
                    }
                }
                // Remove the item from the list of actions.
                unset( $actions_by_id[ $updating ] );
                // Reduce the limit.
                $limit--;
            }
            // Flush our existing meta.
            $sql = "DELETE FROM `{$this->db->prefix}nf3_action_meta` WHERE parent_id IN(" . implode( ', ', $flush_ids ) . ")";
            $this->query( $sql );
            // Insert our updated meta.
            $sql = "INSERT INTO `{$this->db->prefix}nf3_action_meta` ( parent_id, `key`, value, meta_key, meta_value ) VALUES " . implode( ', ', $meta_items );
            $this->query( $sql );
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
            // If all steps have not been completed...
            if ( ! empty( $this->running[ 0 ][ 'forms' ] ) ) {
                // Increment our step count.
                $this->running[ 0 ][ 'current' ] = intval( $this->running[ 0 ][ 'current' ] ) + 1;
            }
            // TODO: Update the stage of the current form in the upgrades table.
        }
        // Get a copy of the cache.
        $sql = "SELECT cache FROM `{$this->db->prefix}nf3_upgrades` WHERE id = " . intval( $form[ 'ID' ] );
        $result = $this->db->query( $sql );
        $cache = maybe_unserialize( $result[ 0 ][ 'cache' ] );
        // For each action in the cache...
        foreach( $cache[ 'actions' ] as &$action ) {
            // If we have a new ID for this action...
            if ( isset( $insert_ids[ $action[ 'id' ] ] ) ) {
                // Update it.
                $action[ 'id' ] = intval( $insert_ids[ $action[ 'id' ] ] );
            }
            // TODO: Might also need to append some new settings here (Label)?
        }
        // Save the cache.
        $sql = "UPDATE `{$this->db->prefix}nf3_upgrades` SET cache = " . serialize( $cache ) . " WHERE id = " . intval( $form[ 'ID' ] );
        $this->query( $sql );
        // Prepare to output our number of steps and current step.
        $this->response[ 'stepsTotal' ] = $this->running[ 0 ][ 'steps' ];
        $this->response[ 'currentStep' ] = $this->running[ 0 ][ 'current' ];
        // If we do not have locked processing...
        if ( ! $this->lock_process ) {
            // If all steps have been completed...
            if ( empty( $this->running[ 0 ][ 'forms' ] ) ) {
                // Run our cleanup method.
                $this->cleanup();
            }
        }
        // Record our current location in the process.
        update_option( 'ninja_forms_doing_required_updates', $this->running );
        // Prepare to output the number of updates remaining.
        $this->response[ 'updatesRemaining' ] = count( $this->running );
        // Respond to the AJAX call.
        $this->respond();
    }


    /**
     * Function to run any setup steps necessary to begin processing.
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
        // Record our current step (defaulted to 1 here).
        $this->running[ 0 ][ 'current' ] = 1;
    }


    /**
     * Function to cleanup any lingering temporary elements of a required update after completion.
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
     * @param $value (String) The value to be escaped for SQL.
     * @return (String) The escaped (and possibly serialized) value of the string.
     */
    public function prepare( $value )
    {
        $escaped = $this->db->escape_by_ref( $value );
        // Serialize the value if necessary.
        $escaped = maybe_serialize( $escaped );

        return $escaped;
    }


    /**
     * Function used to call queries that are gated by debug.
     * 
     * @param $sql (String) The query to be run.
     * @return (Object) The response to the wpdb query call.
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
     * Function to delete unncessary items from our existing tables.
     * 
     * @param $items (Array) The list of IDs to be deleted.
     */
    public function delete( $items )
    {
        // Delete all meta for those actions.
        $sql = "DELETE FROM `{$this->db->prefix}nf3_action_meta` WHERE parent_id IN(" . implode( ', ', $items ) . ")";
        $this->query( $sql );
        // Delete the actions.
        $sql = "DELETE FROM `{$this->db->prefix}nf3_actions` WHERE id IN(" . implode( ', ', $items ) . ")";
        $this->query( $sql );
    }


    /**
     * Function to run our table migrations.
     */
    public function migrate()
    {
        $migrations = new NF_Database_Migrations();
        $migrations->do_upgrade( 'cache_collate_actions' );
    }

}