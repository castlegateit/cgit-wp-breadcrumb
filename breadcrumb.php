<?php

namespace Cgit;

class Breadcrumb
{
    /**
     * List of breadcrumb entries
     */
    public $breadcrumb = [];

    /**
     * Separator
     */
    public $sep;

    /**
     * Conditional functions
     */
    public $conditions = [
        'is_front_page',
        'is_home',
        'is_page',
        'is_singular',
        'is_category',
        'is_tag',
        'is_tax',
        'is_search',
        'is_day',
        'is_month',
        'is_year',
        'is_post_type_archive',
        'is_archive',
        'is_404',
    ];

    /**
     * Constructor
     */
    public function __construct($sep = ' / ', $home = false, $index = false)
    {
        // Default post type object
        $obj = get_post_type_object('post');

        // Default values
        $this->home = esc_url(home_url('/'));
        $this->homeName = $home ?: 'Home';
        $this->indexName = $index ?: $obj->labels->name;
        $this->sep = $sep;

        // Initial breadcrumb items
        $this->breadcrumb[] = '<a href="' . $this->home . '">'
            . $this->homeName . '</a>';

        // Add items
        $this->update();
    }

    /**
     * Render breadcrumb
     */
    public function render()
    {
        return implode($this->sep, $this->breadcrumb);
    }

    /**
     * Add items
     *
     * Checks each WordPress conditional function in turn until it finds one
     * that returns true, then calls the relevant method.
     */
    public function update()
    {
        foreach ($this->conditions as $condition) {
            if ($condition()) {
                $method = $this->camelize($condition);
                $this->$method();
                break;
            }
        }
    }

    /**
     * Convert snake case to camel case
     */
    public function camelize($str)
    {
        return lcfirst(str_replace('_', '', ucwords($str, '_')));
    }

    /**
     * Front page
     */
    public function isFrontPage()
    {
        // do nothing
    }

    /**
     * Home page
     *
     * If the front page is a static page, the "home" page is the main posts
     * index page.
     */
    public function isHome()
    {
        $this->breadcrumb[] = $this->indexName;
    }

    /**
     * Page
     */
    public function isPage()
    {
        global $post;

        $parent = $post->post_parent;

        if ($parent) {
            while ($parent) {
                $url = get_permalink($parent);
                $title = get_the_title($parent);
                $ancestor = get_post($parent);
                $parent = $ancestor->post_parent;
                $item = '<a href="' . $url . '">' . $title . '</a>';

                array_splice($this->breadcrumb, 1, 0, $item);
            }
        }

        $this->breadcrumb[] = get_the_title();
    }

    /**
     * Singular
     */
    public function isSingular()
    {
        global $post;

        $type = get_post_type($post);
        $object = get_post_type_object($type);
        $url = get_post_type_archive_link($type);
        $name = $object->labels->name;

        // Blog posts are a special case
        if ($type == 'post') {
            $url = get_permalink(get_option('page_for_posts'));
            $name = $this->indexName;
        }

        // Only add the post type index link if the item is not a post type or
        // if the site has a static front page.
        if ($type != 'post' || get_option('show_on_front') == 'page') {
            $this->breadcrumb[] = '<a href="' . $url . '">' . $name . '</a>';
        }

        $this->breadcrumb[] = get_the_title();
    }

    /**
     * Category
     */
    public function isCategory()
    {
        $this->breadcrumb[] = 'Category';
        $this->breadcrumb[] = single_cat_title('', false);
    }

    /**
     * Tag
     */
    public function isTag()
    {
        $this->breadcrumb[] = 'Tag';
        $this->breadcrumb[] = single_tag_title('', false);
    }

    /**
     * Taxonomy
     */
    public function isTax()
    {
        $tax = get_taxonomy(get_query_var('taxonomy'));
        $term = get_term_by('slug', get_query_var('term'), $tax->name);
        $this->breadcrumb[] = $tax->labels->name;
        $this->breadcrumb[] = $term->name;
    }

    /**
     * Search
     */
    public function isSearch()
    {
        $this->breadcrumb[] = 'Search results';
    }

    /**
     * Day
     */
    public function isDay()
    {
        $this->breadcrumb[] = get_the_date();
    }

    /**
     * Month
     */
    public function isMonth()
    {
        $this->breadcrumb[] = get_the_date('F Y');
    }

    /**
     * Year
     */
    public function isYear()
    {
        $this->breadcrumb[] = get_the_date('Y');
    }

    /**
     * Post type archive
     */
    public function isPostTypeArchive()
    {
        $this->breadcrumb[] = post_type_archive_title('', false);
    }

    /**
     * Archive
     */
    public function isArchive()
    {
        $this->breadcrumb[] = 'Archive';
    }

    /**
     * 404
     */
    public function is404()
    {
        $this->breadcrumb[] = 'Page not found';
    }
}
