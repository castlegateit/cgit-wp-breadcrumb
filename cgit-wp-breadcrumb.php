<?php

/*

Plugin Name: Castlegate IT WP Breadcrumb
Plugin URI: http://github.com/castlegateit/cgit-wp-breadcrumb
Description: Simple breadcrumb navigation for WordPress.
Version: 2.0
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

// Load plugin
add_action('plugins_loaded', function() {
    require_once __DIR__ . '/src/autoload.php';
    require_once __DIR__ . '/functions.php';
    require_once __DIR__ . '/shortcodes.php';
});
