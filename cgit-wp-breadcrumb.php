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

include dirname(__FILE__) . '/breadcrumb.php';

/**
 * Function to return breadcrumb
 */
function cgit_breadcrumb($sep = ' / ', $home = false, $index = false) {
    $breadcrumb = new Cgit\Breadcrumb($sep, $home, $index);

    return $breadcrumb->render();
}

/**
 * Breadcrumb shortcode
 */
add_shortcode('breadcrumb', function($atts) {
    $defaults = array(
        'sep' => ' / ',
        'home' => false,
        'index' => false,
    );

    $atts = shortcode_atts($defaults, $atts);
    $breadcrumb = new Cgit\Breadcrumb($atts['sep'], $atts['home'], $atts['index']);

    return $breadcrumb->render();
});
