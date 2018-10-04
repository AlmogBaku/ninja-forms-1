<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_Batch_Process
 */
class NF_Admin_Processes_ImportForm extends NF_Abstracts_BatchProcess
{
    protected $_slug = 'import_form';

    private $fields_per_step = 20;

    protected $form;

    /**
     * Store an array of columns that we want to store in our table rather than meta.
     *
     * This array stores the column name and the name of the setting that it maps to.
     * 
     * The format is:
     *
     * array( 'COLUMN_NAME' => 'SETTING_NAME' )
     */
    protected $forms_db_columns = array(
        'title'                     => 'title',
        'created_at'                => 'created_at',
        'form_title'                => 'title',
        'default_label_pos'         => 'default_label_pos',
        'show_title'                => 'show_title',
        'clear_complete'            => 'clear_complete',
        'hide_complete'             => 'hide_complete',
        'logged_in'                 => 'logged_in',
        'seq_num'                   => 'seq_num',
    );

    protected $fields_db_columns = array(
        'parent_id'                 => 'parent_id',
        'id'                        => 'id',
        'key'                       => 'key',
        'type'                      => 'type',
        'label'                     => 'label',
        'field_key'                 => 'key',
        'field_label'               => 'label',
        'order'                     => 'order',
        'required'                  => 'required',
        'default_value'             => 'default',
        'label_pos'                 => 'label_pos',
        'personally_identifiable'   => 'personally_identifiable',
    );

    protected $actions_db_columns = array(
        'title'                     => 'title',
        'key'                       =>'key',
        'type'                      =>'type',
        'active'                    =>'active',
        'parent_id'                 =>'parent_id',
        'created_at'                =>'created_at',
        'updated_at'                =>'updated_at',
        'label'                     =>'label',
    );

    /**
     * Function to run any setup steps necessary to begin processing.
     */
    public function startup()
    {
        

        // If we aren't passed any form content, bail.
        if ( empty ( $_POST[ 'extraData' ][ 'content' ] ) ) {
            // TODO: When we add error handling to the batch processor, this should be revisited.
            $this->batch_complete();
        }

        $data = explode( ';base64,', $_POST[ 'extraData' ][ 'content' ] );
        $data = base64_decode( $data[ 1 ] );
        $decoded_data = json_decode( WPN_Helper::json_cleanup( html_entity_decode( $data ) ), true );
        
        // Try to utf8 decode our results.
        $data = WPN_Helper::utf8_decode( $decoded_data );

        // If json_encode returns false, then this is an invalid utf8 decode.
        if ( ! json_encode( $data ) ) {
            $data = $decoded_data;
        }

        // $data is now a form array.
        $this->form = $data;

        /**
         * Check to see if we've got new field columns.
         *
         * We do this here instead of the get_sql_queries() method so that we don't hit the db multiple times.
         */
        $sql = "SHOW COLUMNS FROM {$this->_db->prefix}nf3_fields LIKE 'field_key'";
        $results = $this->_db->get_results( $sql );

        /**
         * If we don't have the field_key column, we need to remove our new columns.
         *
         * Also, set our db stage 1 tracker to false.
         */
        if ( empty ( $results ) ) {
            unset( $this->actions_db_columns[ 'label' ] );
            $db_stage_one_complete = false;
        } else {
            // Add a form value that stores whether or not we have our new DB columns.
            $db_stage_one_complete = true;            
        }

        $this->form[ 'db_stage_one_complete' ] = $db_stage_one_complete;
    }

    /**
     * On processing steps after the first, we need to grab our data from our saved option.
     * 
     * @since  UPDATE_VERSION_ON_MERGE
     * @return void
     */
    public function restart()
    {
        // Get our remaining fields from the database.
        $this->form = get_option( 'nf_import_form', $this->form, array() );
    }

    /**
     * Function to loop over the batch.
     */
    public function process()
    {
        /**
         * Check to see if our $this->form var contains an 'ID' index.
         *
         * If it doesn't, then we need to:
         *     Insert our Form.
         *         Insert our Form Meta.
         *         Insert our Actions.
         *         Insert our Action Meta.
         *         Unset [ 'settings' ] and [ 'actions' ] from $this->form.
         *         Update $this->form[ 'ID' ].
         *     Save our processing option.
         *     Move on to the next step.
         */
        if ( ! isset ( $this->form[ 'ID' ] ) ) {
            $this->insert_form();
        } else { // We have a form ID set.
            $this->insert_fields();
        }

        // If we don't have any more fields to insert, we're done.
        if ( empty( $this->form[ 'fields' ] ) ) {
            // Update our form cache for the new form.
            WPN_Helper::build_nf_cache( $this->form[ 'ID' ] );
            // We're done with this batch process.
            $this->batch_complete();
        } else { // We have fields left to process.
            /**
             * If we have fields left, we need to reset the index.
             * Since fields is a non-associative array, we are looping over it by sequential numeric index.
             * Resetting the index ensures we always have a 0 -> COUNT() keys.
             */
            $this->form[ 'fields' ] = array_values( $this->form[ 'fields' ] );
            // Save our progress.
            update_option( 'nf_import_form', $this->form, 'no' );
            // Move on to the next step in processing.
            $this->next_step();
        }
    }

    /**
     * Function to cleanup any lingering temporary elements of a batch process after completion.
     */
    public function cleanup()
    {
        // Remove the option we used to track between
        delete_option( 'nf_import_form' );
        // Return our new Form ID
        $this->response[ 'form_id' ] = $this->form[ 'ID' ];
    }

    /*
     * Get Steps
     * Determines the amount of steps needed for the step processors.
     *
     * @return int of the number of steps.
     */
    public function get_steps()
    {
        /**
         * We want to run a step for every $this->fields_per_step fields on this form.
         *
         * If we have no fields, then we want to return 0.
         */
        if ( ! isset ( $this->form[ 'fields' ] ) || empty ( $this->form[ 'fields' ] ) ) {
            return 0;
        }

        $steps = count( $this->form[ 'fields' ] ) / $this->fields_per_step;
        $steps = ceil( $steps );
        return $steps;
    }

    public function insert_form()
    {
        /**
         * Insert our form using $this->_db->insert by building an array of column => value pairs and %s, %d types.
         * 
         */
        $insert_columns = array();
        $insert_columns_types = array();
        foreach ( $this->forms_db_columns as $column_name => $setting_name ) {
            $insert_columns[ $column_name ] = $this->form[ 'settings' ][ $setting_name ];
            if ( is_numeric( $this->form[ 'settings' ][ $setting_name ] ) ) {
                array_push( $insert_columns_types, '%d' );
            } else {
                array_push( $insert_columns_types, '%s' );
            }

            // Remove this setting from our $this->form[ 'settings' ] array.
            // unset( $this->form[ 'settings' ][ $setting_name ] );
        }

        $this->_db->insert( "{$this->_db->prefix}nf3_forms", $insert_columns, $insert_columns_types );

        // Update our form ID with the newly inserted row ID.
        $this->form[ 'ID' ] = $this->_db->insert_id;

        $this->insert_form_meta();
        $this->insert_actions();

        // Remove our settings and actions array items.
        unset( $this->form[ 'settings' ], $this->form[ 'actions' ] );
    }

    public function insert_form_meta()
    {
        /**
         * Insert Form Meta.
         * 
         * Loop over our remaining form settings that we need to insert into meta.
         * Add them to our "Values" string for insertion later.
         */
        $insert_values = '';

        foreach( $this->form[ 'settings' ] as $meta_key => $meta_value ) {
            $meta_value = maybe_serialize( $meta_value );
            $this->_db->escape_by_ref( $meta_value );
            $insert_values .= "( {$this->form[ 'ID' ]}, '{$meta_key}', '{$meta_value}'";
            if ( $this->form[ 'db_stage_one_complete'] ) {
                $insert_values .= ", '{$meta_key}', '{$meta_value}'";
            }
            $insert_values .= "),";
        }

        // Remove the trailing comma.
        $insert_values = rtrim( $insert_values, ',' );
        $insert_columns = '`parent_id`, `key`, `value`';
        if ( $this->form[ 'db_stage_one_complete'] ) {
            $insert_columns .= ', `meta_key`, `meta_value`';
        }
        
        // Create SQL string.
        $sql = "INSERT INTO {$this->_db->prefix}nf3_form_meta ( {$insert_columns} ) VALUES {$insert_values}";
        // Run our SQL query.
        $this->_db->query( $sql );
    }

    public function insert_actions()
    {
        /**
         * Insert Actions and Action Meta.
         *
         * Loop over actions for this form and insert actions and action meta.
         */
        foreach( $this->form[ 'actions' ] as $action_settings ) {
            $action_settings[ 'parent_id' ] = $this->form[ 'ID' ];
            // Array that tracks which settings need to be meta and which are columns.
            $action_meta = $action_settings;
            $insert_columns = array();
            $insert_columns_types = array();
            // Loop over all our action columns to get their values.
            foreach ( $this->actions_db_columns as $column_name => $setting_name ) {
                $insert_columns[ $column_name ] = $action_settings[ $setting_name ];
                if ( is_numeric( $action_settings[ $setting_name ] ) ) {
                    array_push( $insert_columns_types, '%d' );
                } else {
                    array_push( $insert_columns_types, '%s' );
                }
                // Remove this setting from our action meta tracking array.
                // unset( $action_meta[ $column_name ] );
            }

            // Insert Action
            $this->_db->insert( "{$this->_db->prefix}nf3_actions", $insert_columns, $insert_columns_types );
            
            // Get our new action ID.
            $action_id = $this->_db->insert_id;

            // Insert Action Meta.
            $insert_values = '';
            /**
             * Anything left in the $action_meta array should be inserted as meta.
             *
             * Loop over each of our settings and add it to our insert sql string.
             */
            $insert_values = '';
            foreach ( $action_meta as $meta_key => $meta_value ) {
                $meta_value = maybe_serialize( $meta_value );
                $this->_db->escape_by_ref( $meta_value );
                $insert_values .= "( {$action_id}, '{$meta_key}', '{$meta_value}'";
                if ( $this->form[ 'db_stage_one_complete'] ) {
                    $insert_values .= ", '{$meta_key}', '{$meta_value}'";
                }
                $insert_values .= "),";
            }
            
            // Remove the trailing comma.
            $insert_values = rtrim( $insert_values, ',' );
            $insert_columns = '`parent_id`, `key`, `value`';
            if ( $this->form[ 'db_stage_one_complete'] ) {
                $insert_columns .= ', `meta_key`, `meta_value`';
            }
            // Create SQL string.
            $sql = "INSERT INTO {$this->_db->prefix}nf3_action_meta ( {$insert_columns} ) VALUES {$insert_values}";

            // Run our SQL query.
            $this->_db->query( $sql );
        }
    }

    public function insert_fields()
    {
        // Remove new field table columns if we haven't completed stage one of our DB conversion.
        if ( ! $this->form[ 'db_stage_one_complete' ] ) {
            // Remove field columns added after stage one.
            unset( $this->fields_db_columns[ 'field_key' ] );
            unset( $this->fields_db_columns[ 'field_label' ] );
            unset( $this->fields_db_columns[ 'order' ] );
            unset( $this->fields_db_columns[ 'required' ] );
            unset( $this->fields_db_columns[ 'default_value' ] );
            unset( $this->fields_db_columns[ 'label_pos' ] );
            unset( $this->fields_db_columns[ 'personally_identifiable' ] );
        }

        /**
         * If we have a Form ID set, then we've already inserted our Form, Form Meta, Actions, and Action Meta.
         * All we have left to insert are fields.
         *
         * Loop over our fields array and insert up to $this->fields_per_step.
         * After we've inserted the field, unset it from our form array.
         * Update our processing option with $this->form.
         * Respond with the remaining steps.
         */
        
        /**
         * Loop over our field array up to $this->fields_per_step.
         */
        for ( $i = 0; $i < $this->fields_per_step; $i++ ) {
            // If we don't have a field, skip this $i.
            if ( ! isset ( $this->form[ 'fields' ][ $i ] ) ) continue;

            $field_settings = $this->form[ 'fields' ][ $i ];
            $field_settings[ 'parent_id' ] = $this->form[ 'ID' ];
            // Array that tracks which settings need to be meta and which are columns.
            $field_meta = $field_settings;
            $insert_columns = array();
            $insert_columns_types = array();
            // Loop over all our action columns to get their values.
            foreach ( $this->fields_db_columns as $column_name => $setting_name ) {
                $insert_columns[ $column_name ] = $field_settings[ $setting_name ];
                if ( is_numeric( $field_settings[ $setting_name ] ) ) {
                    array_push( $insert_columns_types, '%d' );
                } else {
                    array_push( $insert_columns_types, '%s' );
                }
            }

            // Add our field to the database.
            $this->_db->insert( "{$this->_db->prefix}nf3_fields", $insert_columns, $insert_columns_types );

            /**
             * Get our new field ID.
             */
            $field_id = $this->_db->insert_id;

            $insert_values = '';
            /**
             * Anything left in the $field_meta array should be inserted as meta.
             *
             * Loop over each of our settings and add it to our insert sql string.
             */
            $insert_values = '';
            foreach ( $field_meta as $meta_key => $meta_value ) {
                $meta_value = maybe_serialize( $meta_value );
                $this->_db->escape_by_ref( $meta_value );
                $insert_values .= "( {$field_id}, '{$meta_key}', '{$meta_value}'";
                if ( $this->form[ 'db_stage_one_complete'] ) {
                    $insert_values .= ", '{$meta_key}', '{$meta_value}'";
                }
                $insert_values .= "),";
            }
            
            // Remove the trailing comma.
            $insert_values = rtrim( $insert_values, ',' );
            $insert_columns = '`parent_id`, `key`, `value`';
            if ( $this->form[ 'db_stage_one_complete'] ) {
                $insert_columns .= ', `meta_key`, `meta_value`';
            }
            // Create SQL string.
            $sql = "INSERT INTO {$this->_db->prefix}nf3_field_meta ( {$insert_columns} ) VALUES {$insert_values}";

            // Run our SQL query.
            $this->_db->query( $sql );

            // Remove this field from our fields array.
            unset( $this->form[ 'fields' ][ $i ] );
        }
    }

}