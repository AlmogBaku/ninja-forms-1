<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_Batch_Process
 */
class NF_Admin_Processes_ImportForm extends NF_Abstracts_BatchProcess
{

    protected $expired_subs = array();

    private $response = array(
        'batch_complete' => false
    );

    private $_slug = 'import_form';

    private $fields_per_step = 20;

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

    private $fields_db_columns = array(
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

    private $actions_db_columns = array(
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
     * Constructor
     */
    public function __construct( $data = array() )
    {
        //Bail if we aren't in the admin.
        if ( ! is_admin() )
            return false;
  
        // Run process.
        $this->process();
    }

    /**
     * Function to loop over the batch.
     */
    public function process()
    {
        global $wpdb;

        if ( ! get_option( 'nf_doing_' . $this->_slug ) ) {
            // Run the startup process.
            $this->startup();
        } // Otherwise... (We've already run startup.)
        else {
            // Get our remaining fields from the database.
            $data = get_option( 'nf_import_form', $this->form );
            $this->form = $data;
        }

        /**
         * Check to see if our $this->form var contains an 'ID' index.
         *
         * If it doesn't, then we need to:
         *     Insert our Form.
         *     Insert our Form Meta.
         *     Insert our Actions.
         *     Insert our Action Meta.
         *     Unset [ 'settings' ] and [ 'actions' ] from $this->form.
         *     Update $this->form[ 'ID' ].
         *     Save our processing option.
         *     Respond with remaining steps.
         */
        if ( ! isset ( $this->form[ 'ID' ] ) ) {
            /**
             * Insert our form using $wpdb->insert by building an array of column => value pairs and %s, %d types.
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

            $wpdb->insert( "{$wpdb->prefix}nf3_forms", $insert_columns, $insert_columns_types );

            // Update our form ID with the newly inserted row ID.
            $this->form[ 'ID' ] = $wpdb->insert_id;

            /**
             * Insert Form Meta.
             * 
             * Loop over our remaining form settings that we need to insert into meta.
             * Add them to our "Values" string for insertion later.
             */
            $insert_values = '';
            foreach( $this->form[ 'settings' ] as $meta_key => $meta_value ) {
                $meta_value = maybe_serialize( $meta_value );
                $wpdb->escape_by_ref( $meta_value );
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
            $sql = "INSERT INTO {$wpdb->prefix}nf3_form_meta ( {$insert_columns} ) VALUES {$insert_values}";
            // Run our SQL query.
            $wpdb->query( $sql );

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
                $wpdb->insert( "{$wpdb->prefix}nf3_actions", $insert_columns, $insert_columns_types );
                
                // Get our new action ID.
                $action_id = $wpdb->insert_id;

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
                    $wpdb->escape_by_ref( $meta_value );
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
                $sql = "INSERT INTO {$wpdb->prefix}nf3_action_meta ( {$insert_columns} ) VALUES {$insert_values}";

                // Run our SQL query.
                $wpdb->query( $sql );
            }

            // Remove our settings and actions array items.
            unset( $this->form[ 'settings' ], $this->form[ 'actions' ] );
        } else { // We have a form ID set.
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

                    // Remove this setting from our action meta tracking array.
                    // unset( $field_meta[ $column_name ] );
                }

                // Add our field to the database.
                $wpdb->insert( "{$wpdb->prefix}nf3_fields", $insert_columns, $insert_columns_types );

                /**
                 * Add our field meta to the database.
                 *
                 * Get our new action ID.
                 */
                $field_id = $wpdb->insert_id;

                // Insert Action Meta.
                $insert_values = '';
                /**
                 * Anything left in the $field_meta array should be inserted as meta.
                 *
                 * Loop over each of our settings and add it to our insert sql string.
                 */
                $insert_values = '';
                foreach ( $field_meta as $meta_key => $meta_value ) {
                    $meta_value = maybe_serialize( $meta_value );
                    $wpdb->escape_by_ref( $meta_value );
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
                $sql = "INSERT INTO {$wpdb->prefix}nf3_field_meta ( {$insert_columns} ) VALUES {$insert_values}";

                // Run our SQL query.
                $wpdb->query( $sql );

                // Remove this field from our fields array.
                unset( $this->form[ 'fields' ][ $i ] );
            }            
        }

        // If we don't have any more fields to insert, we're done.
        if ( empty( $this->form[ 'fields' ] ) ) {
            // Update our form cache for the new form.
            WPN_Helper::build_nf_cache( $this->form[ 'ID' ] );
            // Run our cleanup process.
            $this->cleanup();
        } else { // We have fields left to process.
            /**
             * If we have fields left, we need to reset the index.
             * Since fields is a non-associative array, we are looping over it by sequential numeric index.
             * Resetting the index ensures we always have a 0 -> COUNT() keys.
             */
            $this->form[ 'fields' ] = array_values( $this->form[ 'fields' ] );
            // Get the number of steps we have left. 
            $this->response[ 'step_remaining' ] = $this->get_steps();
            // Save our progress.
            update_option( 'nf_import_form', $this->form, false );
        }

        echo wp_json_encode( $this->response );
        wp_die();
    }

    /**
     * Function to run any setup steps necessary to begin processing.
     */
    public function startup()
    {
        global $wpdb;

        $data = explode( ';base64,', $_POST[ 'extraData' ][ 'content' ] );
        $data = base64_decode( $data[ 1 ] );
        $data = json_decode( $data, true );

        // $data is now a form array.
        $this->form = $data;

        // Determine how many steps this will take.
        $this->response[ 'step_total' ] = $this->get_steps();

        /**
         * Check to see if we've got new field columns.
         *
         * We do this here instead of the get_sql_queries() method so that we don't hit the db multiple times.
         */
        $sql = "SHOW COLUMNS FROM {$wpdb->prefix}nf3_fields LIKE 'field_key'";
        $results = $wpdb->get_results( $sql );
        
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

        $this->form[ 'stage_one_complete' ] = $db_stage_one_complete;

        add_option( 'nf_doing_' . $this->_slug, 'true', false );
    }

    /**
     * Function to cleanup any lingering temporary elements of a batch process after completion.
     */
    public function cleanup()
    {
        // Delete our options.
        delete_option('nf_doing_' . $this->_slug );
        delete_option( 'nf_import_form' );

        // Tell our JS that we're done.
        $this->response[ 'batch_complete' ] = true;
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
         * If we have no fields, then we want to return 1.
         */
        if ( ! isset ( $this->form[ 'fields' ] ) || empty ( $this->form[ 'fields' ] ) ) {
            $steps = 0;
        }

        $steps = count( $this->form[ 'fields' ] ) / $this->fields_per_step;
        $steps = ceil( $steps );
        return $steps;
    }

}