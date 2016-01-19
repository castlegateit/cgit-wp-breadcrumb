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

/**
 * Load plugin
 */
add_action('plugins_loaded', function() {
    include dirname(__FILE__) . '/breadcrumb.php';
    include dirname(__FILE__) . '/functions.php';
    include dirname(__FILE__) . '/shortcodes.php';
}, 10);
