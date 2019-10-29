<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_ListImage
 */
class NF_Fields_ListImage extends NF_Abstracts_List
{
    protected $_name = 'listimage';

    protected $_type = 'listimage';

    protected $_nicename = 'Select Image';

    protected $_section = 'common';

    protected $_icon = 'image';

    protected $_templates = 'listimage';

    protected $_settings =  array( 'image_options', 'allow_multi_select', 'list_orientation', 'max_columns' );

    protected $_settings_exclude = array('options');

    // protected $_settings_all_fields = array(
    //     'key', 'label', 'label_pos', 'required', 'classes', 'admin_label', 'help', 'description','allow_multi_select', 'list_orientation', 'max_columns'
    // );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Select Image', 'ninja-forms' );

        add_filter( 'ninja_forms_merge_tag_calc_value_' . $this->_type, array( $this, 'get_calc_value' ), 10, 2 );
    }

    public function get_calc_value( $value, $field )
    {
        if( isset( $field[ 'options' ] ) ) {
            foreach ($field['options'] as $option ) {
                if( ! isset( $option[ 'value' ] ) || $value != $option[ 'value' ] || ! isset( $option[ 'calc' ] ) ) continue;
                return $option[ 'calc' ];
            }
        }
        return $value;
    }
}
