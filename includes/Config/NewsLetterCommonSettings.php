<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return apply_filters( 'ninja_forms_news_letter_common_settings', array(
	/*
      |--------------------------------------------------------------------------
      | Advanced Codes
      |--------------------------------------------------------------------------
     */
    'ninja_forms_ADDON_advanced_commands' => array(
        'id' => 'ninja_forms_ADDON_advanced_commands',
        'type' => 'textbox',
        'label' => __( 'Advanced Commands', 'ninja-forms' ),
    ),
    /*
      |--------------------------------------------------------------------------
      | Support Messages
      |--------------------------------------------------------------------------
     */
    
    'ninja_forms_ADDON_field_map_array' => array(
        'id' => 'ninja_forms_ADDON_field_map_array',
        'type' => 'html',
        'label' => __( 'Field Map Array', 'ninja-forms' ),
        'html' => '',
    ),
    'ninja_forms_ADDON_structured_request_array' => array(
        'id' => 'ninja_forms_ADDON_structured_request_array',
        'type' => 'html',
        'label' => __( 'Structured Request', 'ninja-forms' ),
        'html' => '',
    ),
    'ninja_forms_ADDON_structured_response' => array(
        'id' => 'ninja_forms_ADDON_structured_response',
        'type' => 'html',
        'label' => __( 'Structured Response', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    'ninja_forms_ADDON_raw_response' => array(
        'id' => 'ninja_forms_ADDON_raw_response',
        'type' => 'html',
        'label' => __( 'Raw Response', 'ninja-forms' ),
        'html' => '',
    ),
    /*
      |--------------------------------------------------------------------------
      | Account Information
      |--------------------------------------------------------------------------
     */
    'ninja_forms_ADDON_manual_field_map' => array(
        'id' => 'ninja_forms_ADDON_manual_field_map',
        'type' => 'html',
        'label' => __( 'Field Mapping Lookup', 'ninja-forms' ),
        'html' => '',
    ),
) );