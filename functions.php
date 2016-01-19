<?php

/**
 * Function to return breadcrumb
 */
function cgit_breadcrumb($sep = ' / ', $home = false, $index = false) {
    $breadcrumb = new Cgit\Breadcrumb($sep, $home, $index);

    return $breadcrumb->render();
}
