<?php

// Check for PHP version compatibility.
if (!version_compare(PHP_VERSION, '7.1.0', '>=')) {
    return;
}

include_once plugin_dir_path(__FILE__) . '/bootstrap.php';