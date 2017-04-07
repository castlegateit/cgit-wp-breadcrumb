<?php

/**
 * Return HTML breadcrumb navigation
 *
 * @param string $sep
 * @param mixed $home
 * @param mixed $index
 * @return string
 */
function cgit_breadcrumb($sep = ' / ', $home = false, $index = false)
{
    $args = [];

    if ($home) {
        $args['home'] = $home;
    }

    if ($index) {
        $args['index'] = $index;
    }

    $crumb = new Cgit\Breadcrumb($args);

    return $crumb->render($sep);
}
