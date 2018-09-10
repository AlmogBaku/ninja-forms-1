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
class NF_Updates_CacheCollateFields extends NF_Abstracts_RequiredUpdate
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
        $this->_slug = 'CacheCollateFields';
        $this->_class_name = 'NF_Updates_CacheCollateFields';
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
        $form = array_pop( $this->running[ 0 ][ 'forms' ] );
        
        // Get the fields for that form.
        $fields = Ninja_Forms()->form( $form[ 'ID' ] )->get_fields();
        
        // Setup variables for our SQL methods.
        $field_ids = array();
        $fields_by_id = array();
        $insert = array();
        $delete = array();
        $submission_updates = array();
        
        // For each field...
        foreach ( $fields as $field ) {
            // Add the ID to the list.
            array_push( $field_ids, $field->get_id() );
            $fields_by_id[ $field->get_id() ] = $field->get_settings();
        }
        // Cross reference the Fields table to see if these IDs exist for this Form.
        $sql = "SELECT id FROM `{$this->db->prefix}nf3_fields` WHERE parent_id = {$form[ 'ID' ]}";
        $db_fields = $this->db->get_results( $sql, 'ARRAY_A' );
        $db_field_ids = array();
        // For each field in the fields table...
        foreach ( $db_fields as $field ) {
            // If we have no reference to it in the cache...
            if ( ! in_array( $field[ 'id' ], $field_ids ) ) {
                // Schedule it for deletion.
                array_push( $delete, $field[ 'id' ] );
            } else { // Push the id onto our comparison array.
                array_push( $db_field_ids, $field[ 'id' ] );
            }
        }

        // If we're not continuing an old process...
        if ( ! isset( $form[ 'field_ids' ] ) ) {
            // For each field in the cache...
            foreach ( $field_ids as $field ) {
                // If we have no reference to it in the fields table...
                if ( ! in_array( $field, $db_field_ids ) ) {
                    // Schedule it for insertion.
                    array_push( $insert, $field );
                }
            }
            // Cross reference the Fields table to see if these IDs exist on other Forms.
            $sql = "SELECT id FROM `{$this->db->prefix}nf3_fields` WHERE id IN(" . implode( ', ', $field_ids ) . ") AND parent_id <> {$form[ 'ID' ]}";
            $duplicates = $this->db->get_results( $sql, 'ARRAY_A' );
            // If we got something back...
            // (There were duplicates.)
            if ( ! empty( $duplicates ) ) {
                // For each duplicate...
                foreach ( $duplicates as $duplicate ) {
                    // Schedule it for insertion if it isn't already in our $insert array
                    if ( ! in_array( $duplicate[ 'id' ], $insert ) ) {
                       array_push( $insert, $duplicate[ 'id' ] ); 
                    }
                    
                    // Add this field to our submission_updates array which tracks which field IDs change.
                    $submission_updates[ $duplicate[ 'id' ] ] = true;
                }
            }
        } // Otherwise... (We are continuing.)
        else {
            $field_ids = $form[ 'field_ids' ];
            $insert = $form[ 'insert' ];
            $submission_updates = $form[ 'submission_updates' ];
        }
        // Garbage collection.
        unset( $db_fields );
        unset( $db_field_ids );
        
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
                $settings = $fields_by_id[ $inserting ];
                // Insert into the fields table.
                $sql = "INSERT INTO `{$this->db->prefix}nf3_fields` ( label, `key`, `type`, parent_id, field_label, field_key, `order`, required, default_value, label_pos ) VALUES ( '" . $this->prepare( $settings[ 'label' ] ) . "', '".
                       $this->prepare( $settings[ 'key' ] ) . "', '" .
                       $this->prepare( $settings[ 'type' ] ) . "', " .
                       intval( $form[ 'ID' ] ) . ", '" .
                       $this->prepare( $settings[ 'label' ] ) . "', '" .
                       $this->prepare( $settings[ 'key' ] ) . "', " .
                       intval( $settings[ 'order' ] ) . ", " .
                       intval( $settings[ 'required' ] ) . ", '" .
                       $this->prepare( $settings[ 'default_value' ] ) . "', '" .
                       $this->prepare( $settings[ 'label_pos' ] ) . "' )";
                $this->query( $sql );
                // Set a default new_id for debugging.
                $new_id = 0;
                // If we're not in debug mode...
                if ( ! $this->debug ) {
                    // Get the ID of the new field.
                    $new_id = $this->db->insert_id;
                    $settings[ 'old_field_id' ] = $inserting;
                }
                // Save a reference to this insertion.
                $insert_ids[ $inserting ] = $new_id;

                // Update our submission_updates array with the new ID of this field so that we can use it later.
                if ( isset ( $submission_updates[ $inserting ] ) ) {
                    $submission_updates[ $inserting ] = $new_id; 
                }
                
                // For each meta of the field...
                foreach ( $settings as $meta => $value ) {
                    // If it's not empty...
                    if ( ( ! empty( $value ) || '0' == $value ) ) {
                        // Add the data to the list.
                        array_push( $meta_items, "( " . intval( $new_id ) . ", '" . $meta . "', '" . $this->prepare( $value ) . "', '" . $meta . "', '" . $this->prepare( $value ) . "' )" );
                    }
                }
                // Remove the item from the list of fields.
                unset( $fields_by_id[ $inserting ] );
                // Reduce the limit.
                $limit--;
            }
            // Insert our meta.
            $sql = "INSERT INTO `{$this->db->prefix}nf3_field_meta` ( parent_id, `key`, value, meta_key, meta_value ) VALUES " . implode( ', ', $meta_items );
            $this->query( $sql );
        }

        /**
         * If we have any duplicate field IDs, we need to update any existing submissions with the new field ID.
         *
         * The $submission_updates array will look like:
         *
         * $submission_updates[ original_id ] = new_id;
         *
         * This section:
         *     Checks to see if we have any fields in our $submission_updates array (have a changed ID)
         *     Makes sure that processing isn't locked
         *     Loops over fields in our $submission_updates array
         *     Fetches submissions for the specific form ID
         *     Loops over those submissions and replaces _field_ORIGINALID with _field_NEWID
         */
        
        if ( ! empty ( $submission_updates ) && ! $this->lock_process ) {
            /*
             * Keep track of old field IDs we've used.
             *     Initially, we set our record array to our current submission updates array.
             *     When we finish updating an old field, we remove it from the record array.
             *     When we're done with all fields, we set the submission updates array to the record array.
             */
            $submission_updates_record = $submission_updates;
            // Meta key update limit; How many meta keys do we want to update at a time?
            $meta_key_limit = 200;
            // Loop through submission updates and query the postmeta table for any meta_key values of _field_{old_id}.
            foreach ( $submission_updates as $old_id => $new_id ) {
                // Make sure that we haven't reached our query limit.
                if ( 1 > $limit ) {
                    // Lock processing.
                    $this->lock_process = true;
                    // Exit the loop.
                    break;
                }

                // This sql is designed to grab our old _field_X post meta keys so that we can replace them with new _field_X meta keys.
                $sql = "SELECT
                    old_field_id.meta_id
                    FROM
                    `{$this->db->prefix}posts` p
                    INNER JOIN `{$this->db->prefix}postmeta` old_field_id ON old_field_id.post_id = p.ID
                    AND old_field_id.meta_key = '_field_{$old_id}'
                    INNER JOIN `{$this->db->prefix}postmeta` form_id ON form_id.post_id = p.ID
                    AND form_id.meta_key = '_form_id'

                    WHERE old_field_id.meta_key = '_field_{$old_id}'
                     AND form_id.meta_value = {$form[ 'ID' ]}
                     AND p.post_type = 'nf_sub'
                     LIMIT {$meta_key_limit}";
                // Fetch our sql results.
                $meta_ids = $this->db->get_results( $sql, 'ARRAY_N' );
                // Implode our meta ids so that we can use the result in our update sql.
                $imploded_ids = implode( ',', call_user_func_array( 'array_merge', $meta_ids ) );
                // Update all our fetched meta IDs with the new _field_ meta key.
                $sql = "UPDATE `{$this->db->prefix}postmeta`
                    SET    meta_key = '_field_{$new_id}'
                    WHERE  meta_id IN ( {$imploded_ids} )";
                    
                $this->query( $sql );

                /*
                 * Let's make sure that we're done processing all post meta for this old field ID.
                 * 
                 * If the number of meta rows retrieved equals our limit:
                 *     lock processing
                 *     break out of this loop
                 * Else
                 *     we're done with this old field, remove it from our list
                 *     subtract from our $limit var
                 */
                if ( $meta_key_limit === count( $meta_ids ) ) {
                    // Keep anything else from processing.
                    $this->lock_process = true;
                    // Exit this foreach loop.
                    break;
                } else { // We're done with this old field.
                    // Remove the field ID from our submission array.
                    unset( $submission_updates_record[ $old_id ] );
                    // Decrement our query limit.
                    $limit--;
                }

            } // End foreach
            // Set our submission updates array to our record array so that we remove any completed old ids.
            $submission_updates = $submission_updates_record;
        }
   
        /*
         * At this point, we should only have items to update.
         * 
         * If we have items left to process...
         * AND If processing hasn't been locked...
         */
        if ( ! empty( $field_ids ) && ! $this->lock_process ) {
            // Store the meta items outside the loop for faster insertion.
            $meta_items = array();
            $flush_ids = array();
            // While we still have items to update...
            while ( 0 < count( $field_ids ) ) {
                // If we have hit our limit...
                if ( 1 > $limit ) {
                    // Lock processing.
                    $this->lock_process = true;
                    // Exit the loop.
                    break;
                }
                // Get our item to be updated.
                $updating = array_pop( $field_ids );
                array_push( $flush_ids, $updating );
                $settings = $fields_by_id[ $updating ];
                // Update the fields table.
                $sql = "UPDATE `{$this->db->prefix}nf3_fields` SET label = '" 
                    . $this->prepare( $settings[ 'label' ] ) 
                    . "', `key` = '" . $this->prepare( $settings[ 'key' ] ) 
                    . "', `type` = '" . $this->prepare( $settings[ 'type' ] ) 
                    . "', field_label = '" . $this->prepare( $settings[ 'label' ] ) 
                    . "', field_key = '" . $this->prepare( $settings[ 'key' ] ) 
                    . "', `order` = " . intval( $settings[ 'order' ] ) 
                    . ", required = " . intval( $settings[ 'required' ] )
                    . ", default_value = '" . $this->prepare( $settings[ 'default_value' ] )
                    . "', label_pos = '" . $this->prepare( $settings[ 'label_pos' ] )
                    . "' WHERE id = " . intval( $updating );
                $this->query( $sql );
                // For each meta of the field...
                foreach ( $settings as $meta => $value ) {
                    // If it's not empty...
                    if ( ( ! empty( $value ) || '0' == $value ) ) {
                        // Add the data to the list.
                        array_push( $meta_items, "( " . intval( $updating ) . ", '" . $meta . "', '" . $this->prepare( $value ) . "', '" . $meta . "', '" . $this->prepare( $value ) . "' )" );
                    }
                }
                // Remove the item from the list of fields.
                unset( $fields_by_id[ $updating ] );
                // Reduce the limit.
                $limit--;
            }
            // Flush our existing meta.
            $sql = "DELETE FROM `{$this->db->prefix}nf3_field_meta` WHERE parent_id IN(" . implode( ', ', $flush_ids ) . ")";
            $this->query( $sql );
            // Insert our updated meta.
            $sql = "INSERT INTO `{$this->db->prefix}nf3_field_meta` ( parent_id, `key`, value, meta_key, meta_value ) VALUES " . implode( ', ', $meta_items );
            $this->query( $sql );
        }
        
        // If we have locked processing...
        if ( $this->lock_process ) {
            // Reset the field_ids array.
            $field_ids = array();
            // For each field left to process...
            foreach ( $fields_by_id as $id => $field ) {
                // If we've not already processed this field...
                if ( in_array( $id, $form[ 'field_ids' ] ) ) {
                    // Save a reference to its ID.
                    array_push( $field_ids, $id );
                }
            }
            // Store our current data location.
            $form[ 'insert' ] = $insert;
            $form[ 'field_ids' ] = $field_ids;
            $form[ 'submission_updates' ] = $submission_updates;
            array_push( $this->running[ 0 ][ 'forms' ], $form );
        } // Otherwise... (The step is complete.)
        else {
            // If all steps have not been completed...
            if ( ! empty( $this->running[ 0 ][ 'forms' ] ) ) {
                // Increment our step count.
                $this->running[ 0 ][ 'current' ] = intval( $this->running[ 0 ][ 'current' ] ) + 1;
            }
        }
        // // Get a copy of the cache.
        $cache = WPN_Helper::get_nf_cache($form[ 'ID' ] );
        // For each field in the cache...
        foreach( $cache[ 'fields' ] as &$field ) {
            // If we have a new ID for this field...
            if ( isset( $insert_ids[ $field[ 'id' ] ] ) ) {
                // Update it.
                $field[ 'id' ] = intval( $insert_ids[ $field[ 'id' ] ] );
            }
            // TODO: Might also need to append some new settings here (Label)?
        }
        // Save the cache, passing 3 as the current stage.
        WPN_Helper::update_nf_cache( $form[ 'ID' ], $cache, 3 );
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
	 * @param $value (String) The value to be escaped for SQL.
	 * @return (String) The escaped (and possibly serialized) value of the string.
     * 
     * @since UPDATE_VERSION_ON_MERGE
	 */
	public function prepare( $value )
	{
		// Default to the current value to ensure a return type.
		$escaped = $value;
		// If our value isn't of type float...
		if ( ! is_float( $value ) ) {
			// Escape it.
			$escaped = $this->db->_real_escape( $value );
			// Serialize the value if necessary.
			$escaped = maybe_serialize( $escaped );
		}

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
     * Function to delete unncessary items from our existing tables.
     * 
     * @param $items (Array) The list of IDs to be deleted.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function delete( $items )
    {
        // Delete all meta for those fields.
        $sql = "DELETE FROM `{$this->db->prefix}nf3_field_meta` WHERE parent_id IN(" . implode( ', ', $items ) . ")";
        $this->query( $sql );
        // Delete the fields.
        $sql = "DELETE FROM `{$this->db->prefix}nf3_fields` WHERE id IN(" . implode( ', ', $items ) . ")";
        $this->query( $sql );
    }


    /**
     * Function to run our table migrations.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function migrate()
    {
        $migrations = new NF_Database_Migrations();
        $migrations->do_upgrade( 'cache_collate_fields' );
    }

}