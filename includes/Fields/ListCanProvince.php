<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_ListState
 */
class NF_Fields_ListCanProvince extends NF_Abstracts_List
{
    protected $_name = 'listcanprovince';

    protected $_type = 'listcanprovince';

    protected $_nicename = 'Canadian Provinces';

    protected $_section = 'userinfo';

    protected $_icon = 'map-marker';

    protected $_templates = array( 'listcanprovince', 'listselect' );

    protected $_old_classname = 'list-select';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Canadian Provinces', 'ninja-forms' );

        $this->_settings[ 'options' ][ 'value' ] = $this->get_options();
    }

    private function get_options()
    {
        $order = 0;
        $options = array();
        // Option to have no state selected by default.
        $options[] = array(
            'label' => '- ' . __( 'Select Province', 'ninja-forms' ) . ' -',
            'value' => '',
	        'calc' => '',
	        'selected' => 0,
	        'order' => $order,
        );
        $order++;

        foreach( Ninja_Forms()->config( 'CanProvinceList' ) as $label => $value ){
            $options[] = array(
                'label'  => $label,
                'value' => $value,
                'calc' => '',
                'selected' => 0,
                'order' => $order
            );

            $order++;
        }

        return $options;
    }
}