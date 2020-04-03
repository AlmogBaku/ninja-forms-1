<?php

// Check for PHP version compatibility.
if (!version_compare(PHP_VERSION, '7.1.0', '>=')) {
    return;
}

// This is the first module using autoloading, so set that up here for now.
include_once plugin_dir_path(__FILE__) . '../../vendor/autoload.php';

include_once plugin_dir_path(__FILE__) . '/functions.php';
