<?php

// Check for PHP version compatibility.
if (!version_compare(PHP_VERSION, '7.1.0', '>=')) {
    return;
}

// Check user permissions.
if(!current_user_can( apply_filters( 'ninja_forms_admin_parent_menu_capabilities', 'manage_options' ) )) {
    return;
}

// Check site settings.
if( 1 === Ninja_Forms()->get_setting( 'disable_admin_notices', 0 ) ) { 
    return;
}

// This is the first module using autoloading, so set that up here for now.
include_once plugin_dir_path(__FILE__) . '../../vendor/autoload.php';

include_once plugin_dir_path(__FILE__) . '/functions.php';
