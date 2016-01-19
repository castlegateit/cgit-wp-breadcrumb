<?php

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
