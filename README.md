# Castlegate IT WP Breadcrumb #

Castlegate IT WP Breadcrumb adds a simple breadcrumb navigation to WordPress. The `Cgit\Breadcrumb` class can generate breadcrumb items as an array of data, an array of HTML links, or a complete breadcrumb navigation:

~~~ php
$crumb = new Cgit\Breadcrumb();
$foo = $crumb->getItems(); // return array of items
$foo = $crumb->getLinks(); // return array of HTML links
$foo = $crumb->render($sep); // return HTML links separated by $sep
$foo = $crumb->renderList($tag); // return HTML list
~~~

## Default text for front page and posts page

You can specify the text used for the home page and the posts index in the constructor:

~~~ php
$crumb = new Cgit\Breadcrumb([
    'home' => 'Home',
    'index' => 'News',
]);
~~~

You can also use the `cgit_breadcrumb_names` filter to edit the array of front and posts page names.

## Custom HTML

You can customize the HTML used for links, non-links, and the current breadcrumb item using a filter, with `sprintf` strings as templates:

~~~ php
add_filter('cgit_breadcrumb_templates', function ($templates) {
    'url' => '<a href="%2$s" class="cgit-breadcrumb-item link">%1$s</a>',
    'current' => '<span class="cgit-breadcrumb-item current">%s</span>',
    'span' => '<span class="cgit-breadcrumb-item span">%s</span>',
});
~~~

## Functions

For backwards compatibility, the plugin also provides a function for rendering a breadcrumb navigation, equivalent to the `render` method:

~~~ php
$foo = cgit_breadcrumb($sep, $home, $index);
~~~
