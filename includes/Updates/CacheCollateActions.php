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

        // Get the actions for that form.
        $actions = Ninja_Forms()->form( $form[ 'ID' ] )->get_actions();

        // Get the cache for that form.
        $cache = WPN_Helper::get_nf_cache( $form[ 'ID' ] );

        // Setup variables for our SQL methods.
        $action_ids = array();
        $actions_by_id = array();

        // For each action...
        foreach ( $actions as $action ) {
            // Add the ID to the list.
            array_push( $action_ids, $action->get_id() );
            $actions_by_id[ $action->get_id() ] = $action->get_settings();
        }

        // Set our hard limit for the loop.
        $limit = 10;

        // If we're continuing an old process...
        if ( isset( $form[ 'update' ] ) ) {
            // Fetch our remaining udpates.
            $update = $form[ 'update' ];
        } // Otherwise... (We're beginning a new process.)
        else {
            // Copy all IDs to our update list.
            $update = $action_ids;
        }

        // Declare placeholder values.
        $sub_sql = array();
        $meta_values = array();
        // Setup our setting blacklist.
        $blacklist = array(
            'objectType',
            'objectDomain',
            'editActive',
            'title',
            'key',
        );

        // While we have actions to update...
        while ( 0 < count( $update ) ) {
            // If we have hit our limit...
            if ( 1 > $limit ) {
                // Lock processing.
                $this->lock_process = true;
                // Exit the loop.
                break;
            }
            // Get our action to be updated.
            $action = array_pop( $update );
            // Get our settings.
            $settings = $actions_by_id[ $action ];
            // Update the new label column.
            array_push( $sub_sql, "WHEN `id` = " . intval( $action ) . " THEN '" . $this->prepare( $settings[ 'label' ] ) . "'" );
            // For each setting...
            foreach ( $settings as $key => $setting ) {
                // If the key is not blacklisted...
                if ( ! in_array( $key, $blacklist ) ) {
                    // Add the value to be updated.
                    array_push( $meta_values, "WHEN `key` = '{$key}' THEN '" . $this->prepare( $setting ) . "'" );
                }
            }
            $limit--;
        }
        // If we've got updates to run...
        if ( ! empty( $sub_sql ) ) {
            // Update our actions table.
            $sql = "UPDATE `{$this->db->prefix}nf3_actions` SET `label` = CASE " . implode ( ' ', $sub_sql ) . " ELSE `label` END;";
            $this->query( $sql );
            // Update our meta values.
            $sql = "UPDATE `{$this->db->prefix}nf3_action_meta` SET `meta_value` = CASE " . implode( ' ', $meta_values ) . " ELSE `meta_value` END;";
            $this->query( $sql );
        }


        // If we have locked processing...
        if ( $this->lock_process ) {
            // Record that we have more to do.
            $form[ 'update' ] = $update;
            array_push( $this->running[ 0 ][ 'forms' ], $form );
        } // Otherwise... (Processing isn't locked.)
        else {
            // Update our meta keys.
            $sql = "UPDATE `{$this->db->prefix}nf3_action_meta` SET `meta_key` = `key` WHERE `parent_id` IN(" . implode( ',', $action_ids ) . ")";
            $this->query( $sql );
            // Bust the cache.
            $cache[ 'actions' ] = array();
            // For each action...
            foreach ( $actions_by_id as $id => $settings ) {
                // Append the settings for that action to the cache.
                $action = array();
                $action[ 'settings' ] = $settings;
                $action[ 'id' ] = $id;
                array_push( $cache[ 'actions' ], $action );
            }
            // Save the cache, passing 2 as the current stage.
            WPN_Helper::update_nf_cache( $form[ 'ID' ], $cache, 2 );
            // Increment our step count.
            $this->running[ 0 ][ 'current' ] = intval( $this->running[ 0 ][ 'current' ] ) +1;
        }
        // Prepare to output our number of steps and current step.
        $this->response[ 'stepsTotal' ] = $this->running[ 0 ][ 'steps' ];
        $this->response[ 'currentStep' ] = $this->running[ 0 ][ 'current' ];

        // If we do not have locked processing...
        if ( ! $this->lock_process ) {
            // If all steps have been completed...
            if ( empty( $this->running[ 0 ] [ 'forms' ] ) ) {
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
     * Function to delete unncessary items from our existing tables.
     * 
     * @param $items (Array) The list of IDs to be deleted.
     * 
     * @since UPDATE_VERSION_ON_MERGE
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
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    public function migrate()
    {
        $migrations = new NF_Database_Migrations();
        $migrations->do_upgrade( 'cache_collate_actions' );
    }

}