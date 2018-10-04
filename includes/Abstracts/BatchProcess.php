<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_Batch_Process
 */
abstract class NF_Abstracts_BatchProcess
{
    protected $_db;

    /**
     * [$response description]
     * @var array
     */
    protected $response = array(
        'batch_complete' => false
    );

    /**
     * Constructor
     */
    public function __construct( $data = array() )
    {
        //Bail if we aren't in the admin.
        if ( ! is_admin() )
            return false;

        global $wpdb;

        /**
         * Set $_db to $wpdb.
         * This helps us by not requiring us to declare global $wpdb in every class method.
         */
        $this->_db = $wpdb;

        // Run init.
        $this->init();
    }

    /**
     * Decides whether we need to run startup or restart and then calls processing.
     *
     * @since  UPDATE_VERSION_ON_MERGE
     * @return void
     */
    public function init()
    {
        if ( ! get_option( 'nf_doing_' . $this->_slug ) ) {
            // Run the startup process.
            $this->startup();
        } else {
            // Otherwise... (We've already run startup.)
            $this->restart();
        }

        // Determine how many steps this will take.
        $this->response[ 'step_total' ] = $this->get_steps();

        add_option( 'nf_doing_' . $this->_slug, true );

        // Run processing
        $this->process();
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
     * Function to run any setup steps necessary to begin processing.
     */
    public function restart()
    {
        /**
         * This function intentionally left empty.
         */
    }

    public function get_steps()
    {
        return 1;
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

    public function batch_complete()
    {
        // Delete our options.
        delete_option( 'nf_doing_' . $this->_slug );
        // Tell our JS that we're done.
        $this->response[ 'batch_complete' ] = true;

        $this->cleanup();
        $this->respond();
    }

    public function next_step()
    {
        // ..see how many steps we have left, update our option, and send the remaining step to the JS.
        $this->response[ 'step_remaining' ] = $this->get_steps();
        $this->respond();
    }

    public function respond()
    {
        echo wp_json_encode( $this->response );
        wp_die();
    }

}