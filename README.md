# Castlegate IT WP Breadcrumb #

Castlegate IT WP Breadcrumb adds a simple breadcrumb navigation to WordPress. The function `cgit_breadcrumb($sep, $home, $index)` will return a complete breadbrumb navigation, with each item separated by `$sep`. The default separator is ` / `. The `$home` argument is optional and can be used to specify the name of the home page (default "Home"). The `$index` argument is optional and can be used to specify the name of the posts index (default "Posts"). The plugin also provides a shortcode:

    [breadcrumb sep=" / " home="Home" index="News"]

The separator, home, and index arguments are optional.
