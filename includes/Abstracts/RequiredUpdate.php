<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_RequiredUpdate
 */
abstract class NF_Abstracts_RequiredUpdate
{

    protected $_slug = '';

    protected $_requires = array();

    protected $_class_name = '';

    protected $db;

    public $response = array();

    public $debug = false;
    
    public $lock_process = false;

    /**
     * Constructor
     */
    public function __construct( $data = array() )
    {
        // Save a reference to wpdb.
        global $wpdb;
        $this->db = $wpdb;
        //Bail if we aren't in the admin.
        if ( ! is_admin() ) return false;
        // If we weren't provided with a slug or a class name...
        if ( ! isset( $data[ 'slug' ] ) || ! isset( $data[ 'class_name' ] ) ) {
            // Bail.
            return false;
        }
        $this->_slug = $data[ 'slug' ];
        $this->_class_name = $data[ 'class_name' ];
        // Record debug settings if provided.
        if ( isset( $data[ 'debug' ] ) ) $this->debug = $data[ 'debug' ];
    }


    /**
     * Function to loop over the batch.
     */
    public function process()
    {
        /**
         * This function intentionlly left empty.
         */
    }


    /**
     * Function to run any setup steps necessary to begin processing.
     */
    public function startup()
    {
        /**
         * This function intentionally left empty.
         */
    }


    /**
     * Function to cleanup any lingering temporary elements of required updates after completion.
     */
    public function cleanup()
    {
        // Delete our required updates data.
        delete_option( 'ninja_forms_doing_required_updates' );
        // Flag that updates are done.
        update_option( 'ninja_forms_needs_updates', 0 );
        // Output that we're done.
        $this->response[ 'updatesRemaining' ] = 0;
        $this->respond();
    }


    /**
     * Function to dump our JSON response and kill processing.
     */
    public function respond()
    {
        // Dump the response.
        echo( json_encode( $this->response ) );
        // Terminate processing.
        die();
    }


    /**
     * Function to run our table migrations.
     * 
     * @param $callback (String) The callback function in the migration file.
     * 
     * @since UPDATE_VERSION_ON_MERGE
     */
    protected function migrate( $callback )
    {
        $migrations = new NF_Database_Migrations();
        $migrations->do_upgrade( $callback );
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
    protected function query( $sql )
    {
        // If we're not debugging...
        if ( ! $this->debug ) {
            // Run the query.
            return $this->db->query( $sql );
        }
        // Otherwise, return false.
        return false;
    }
}