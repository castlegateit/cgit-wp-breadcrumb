<?php

/**
 * Plugin Name:  Castlegate IT WP Breadcrumb
 * Plugin URI:   https://github.com/castlegateit/cgit-wp-breadcrumb
 * Description:  Simple breadcrumb navigation for WordPress.
 * Version:      3.3.1
 * Requires PHP: 8.2
 * Author:       Castlegate IT
 * Author URI:   https://www.castlegateit.co.uk/
 * License:      MIT
 * Update URI:   https://github.com/castlegateit/cgit-wp-breadcrumb
 */

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

define('CGIT_WP_BREADCRUMB_VERSION', '3.3.1');
define('CGIT_WP_BREADCRUMB_PLUGIN_FILE', __FILE__);
define('CGIT_WP_BREADCRUMB_PLUGIN_DIR', __DIR__);

require_once __DIR__ . '/vendor/autoload.php';
