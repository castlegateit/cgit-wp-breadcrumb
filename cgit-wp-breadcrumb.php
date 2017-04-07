<?php

/*

Plugin Name: Castlegate IT WP Breadcrumb
Plugin URI: http://github.com/castlegateit/cgit-wp-breadcrumb
Description: Simple breadcrumb navigation for WordPress.
Version: 3.0
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

add_action('plugins_loaded', function () {
    require_once __DIR__ . '/classes/autoload.php';
    require_once __DIR__ . '/functions.php';
});
