<?php

/*

Plugin Name: Castlegate IT WP Breadcrumb
Plugin URI: http://github.com/castlegateit/cgit-wp-breadcrumb
Description: Simple breadcrumb navigation for WordPress.
Version: 1.0
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

/**
 * Generate breadcrumb
 */
function cgit_breadcrumb ($sep = ' / ') {

    global $post;

    $home_url   = esc_url( home_url('/') );
    $links = array( "<a href='$home_url'>Home</a>" );

    if ( is_page() ) {

        $parent = $post->post_parent;

        if ($parent) {
            while ($parent) {

                $url      = get_permalink($parent);
                $title    = get_the_title($parent);
                $ancestor = get_post($parent);
                $parent   = $ancestor->post_parent;

                array_splice($links, 1, 0, "<a href='$url'>$title</a>");

            }
        }

        $links[] = get_the_title();

    } elseif ( is_singular() ) {

        $type    = get_post_type($post);
        $object  = get_post_type_object($type);
        $url     = get_post_type_archive_link($type) ?: $home_url;
        $name    = $object->labels->name;
        $links[] = "<a href='$url'>$name</a>";
        $links[] = get_the_title();

    } elseif ( is_category() ) {

        $links[] = 'Category';
        $links[] = single_cat_title('', FALSE);

    } elseif ( is_tag() ) {

        $links[] = 'Tag';
        $links[] = single_tag_title('', FALSE);

    } elseif ( is_tax() ) {

        $tax     = get_taxonomy( get_query_var('taxonomy') );
        $links[] = $tax->labels->name;

    } elseif ( is_search() ) {

        $links[] = 'Search results';

    } elseif ( is_day() ) {

        $links[] = get_the_date();

    } elseif ( is_month() ) {

        $links[] = get_the_date('F Y');

    } elseif ( is_year() ) {

        $links[] = get_the_date('Y');

    } elseif ( is_post_type_archive() ) {

        $links[] = post_type_archive_title('', FALSE);

    } elseif ( is_archive() ) {

        $links[] = 'Archive';

    } elseif ( is_404() ) {

        $links[] = 'Page not found';

    }

    return implode($sep, $links);

}

/**
 * Breadcrumb shortcode
 */
function cgit_breadcrumb_shortcode ($atts) {

    $defaults = array(
        'sep' = ' / ',
    );

    $atts = shortcode_atts($defaults, $atts);

    return cgit_breadcrumb($atts['sep']);

}

add_shortcode('breadcrumb', 'cgit_breadcrumb_shortcode');
