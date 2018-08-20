<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_RequiredUpdate
 */
abstract class NF_Abstracts_RequiredUpdate
{

    protected $_slug = '';

    protected $_requires = array();

    protected $_class_name = '';

    public $debug = false;

    public $response = array();

    /**
     * Constructor
     */
    public function __construct( $data = array() )
    {
        //Bail if we aren't in the admin.
        if ( ! is_admin() ) return false;
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

}