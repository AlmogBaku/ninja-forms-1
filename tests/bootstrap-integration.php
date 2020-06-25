<?php



/**
 * Load WordPress test suite
 */
$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    //Should exist in CI, not locally
    $_tests_dir =  dirname(dirname(__FILE__)) . '/tmp/wordpress-tests-lib';
    if( ! file_exists($_tests_dir) ){
        //Should exist in locally, not in ci
        $_tests_dir = dirname(dirname(__FILE__)) . '/wordpress/tests/phpunit';
    }
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin()
{
    require dirname(dirname(__FILE__)) . '/ninja-forms.php';
}
tests_add_filter('muplugins_loaded', '_manually_load_plugin');


// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';