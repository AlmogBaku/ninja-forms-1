<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_RequiredUpdate
 */
class NF_Updates_StageTwo extends NF_Abstracts_RequiredUpdate
{

    private $data = array();

    private $running = array();
    /**
     * Constructor
     */
    public function __construct( $data = array(), $running )
    {
        $this->_slug = 'stage_two';

        $this->_class_name = 'NF_Updates_StageTwo';

        $this->data = $data;

        $this->running = $running;

        parent::__construct();
    }


    /**
     * Function to loop over the batch.
     */
    public function process()
    {
        // If we've made it here and we are not running then...
        if( ! isset( $this->running[ 0 ][ 'running' ] ) ) {
            // ...run our startup method.
            $this->startup();
        }

    }


    /**
     * Function to run any setup steps necessary to begin processing.
     */
    public function startup()
    {
        $this->running[ 0 ][ 'running' ] = true;
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