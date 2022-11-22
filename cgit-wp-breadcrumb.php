<?php

/*

Plugin Name: Castlegate IT WP Breadcrumb
Plugin URI: http://github.com/castlegateit/cgit-wp-breadcrumb
Description: Simple breadcrumb navigation for WordPress.
Version: 3.2.1
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

require_once __DIR__ . '/classes/autoload.php';
require_once __DIR__ . '/functions.php';

do_action('cgit_breadcrumb_loaded');
