<?php

/**
 * Function to return breadcrumb
 *
 * @param string $sep Breadcrumb link separator
 * @param string|bool $home Home link text
 * @param string|bool $index Posts index link text
 * @return string Rendered HTML output
 */
function cgit_breadcrumb($sep = ' / ', $home = false, $index = false) {
    $breadcrumb = new Cgit\Breadcrumb($sep, $home, $index);

    return $breadcrumb->render();
}
