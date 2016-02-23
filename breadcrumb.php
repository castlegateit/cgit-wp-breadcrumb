<?php

namespace Cgit;

class Breadcrumb
{
    /**
     * List of breadcrumb entries
     *
     * @var array
     */
    public $breadcrumb = [];

    /**
     * Separator
     *
     * @var string
     */
    public $sep;

    /**
     * Conditional functions
     *
     * @var array
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
     *
     * @param string $sep Breadcrumb link separator
     * @param string|bool $home Home link text
     * @param string|bool $index Posts index link text
     * @return void
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
     *
     * @return string HTML breadcrumb output
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
     *
     * @return void
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
     *
     * @param string $str Underscore-separated string
     * @return string Camel-case string
     */
    public function camelize($str)
    {
        return lcfirst(str_replace('_', '', ucwords($str, '_')));
    }

    /**
     * Front page
     *
     * @return void
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
     *
     * @return void
     */
    public function isHome()
    {
        $this->breadcrumb[] = $this->indexName;
    }

    /**
     * Page
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
     */
    public function isCategory()
    {
        $this->breadcrumb[] = 'Category';
        $this->breadcrumb[] = single_cat_title('', false);
    }

    /**
     * Tag
     *
     * @return void
     */
    public function isTag()
    {
        $this->breadcrumb[] = 'Tag';
        $this->breadcrumb[] = single_tag_title('', false);
    }

    /**
     * Taxonomy
     *
     * @return void
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
     *
     * @return void
     */
    public function isSearch()
    {
        $this->breadcrumb[] = 'Search results';
    }

    /**
     * Day
     *
     * @return void
     */
    public function isDay()
    {
        $this->breadcrumb[] = get_the_date();
    }

    /**
     * Month
     *
     * @return void
     */
    public function isMonth()
    {
        $this->breadcrumb[] = get_the_date('F Y');
    }

    /**
     * Year
     *
     * @return void
     */
    public function isYear()
    {
        $this->breadcrumb[] = get_the_date('Y');
    }

    /**
     * Post type archive
     *
     * @return void
     */
    public function isPostTypeArchive()
    {
        $this->breadcrumb[] = post_type_archive_title('', false);
    }

    /**
     * Archive
     *
     * @return void
     */
    public function isArchive()
    {
        $this->breadcrumb[] = 'Archive';
    }

    /**
     * 404
     *
     * @return void
     */
    public function is404()
    {
        $this->breadcrumb[] = 'Page not found';
    }
}
