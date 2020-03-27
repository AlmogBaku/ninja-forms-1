<?php

return [
    'source' => plugin_dir_url(__FILE__) . 'script.js',
    'dependencies' => [ 'jquery' ],
    'version' => filemtime( plugin_dir_path(__FILE__) . 'script.js' ),
    'in_footer' => true
];