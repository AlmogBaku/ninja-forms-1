<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_RequiredUpdate
 */
abstract class NF_Abstracts_RequiredUpdate
{

    protected $_slug = '';

    protected $_requires = array();

    protected $_class_name = '';

    /**
     * Constructor
     */
    public function __construct( $data = array() )
    {
        //Bail if we aren't in the admin.
        if ( ! is_admin() ) return false;

        add_filter( 'ninja_forms_required_updates', array( $this, 'register_update' ) );

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
     * Function to cleanup any lingering temporary elements of a batch process after completion.
     */
    public function cleanup()
    {
        /**
         * This function intentionally left empty.
         */
    }

    /**
     * Register Update
     *
     * Registers required updates and builds our updates array.
     * @since 3.3.13
     *
     * @param $updates(array)
     *
     * @return $updates(array)
     */
    public function register_update( $updates )
    {
        // Add this update to the list.
        $updates[ $this->_slug ] = array(
            'class_name'    => $this->_class_name,
            'requires'      => $this->_requires,
        );

        $completed = get_option( 'ninja_forms_required_updates' );

        if( ! $completed || ! isset( $completed[ $this->_slug ] ) ) {
            update_option( 'ninja_forms_needs_updates', 1 );
        }

        return $updates;
    }

}