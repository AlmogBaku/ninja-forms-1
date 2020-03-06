<?php

// Check for the autoloader - common development issue.
if(file_exists($autoloader = plugin_dir_path(__FILE__) . '/vendor/autoload.php')) {
    require $autoloader;
} else {
    add_action( 'admin_notices', 'ninja_forms_views_autoloader_notice' );
    function ninja_forms_views_autoloader_notice() {
        if( defined('WP_DEBUG') && WP_DEBUG ){
            echo sprintf(
                '<div class="notice notice-error"><p>%s</p></div>',
                __( 'Autoloader not found for Ninja Forms Views - try running <code>composer install</code>' )
            );
        } else {
            echo sprintf(
                '<div class="notice notice-error"><p>%s</p></div>',
                __( 'Ninja Forms Views was unable to load.' )
            );
        }
    }
    return;
}

include_once plugin_dir_path(__FILE__) . '/bootstrap.php';
