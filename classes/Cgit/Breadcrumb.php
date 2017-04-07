<?php

namespace Cgit;

class Breadcrumb
{
    /**
     * List of breadcrumb entries
     *
     * @var array
     */
    protected $items = [];

    /**
     * List of default names
     *
     * @var array
     */
    protected $names = [];

    /**
     * List of WordPress conditional functions
     *
     * @var array
     */
    protected $conditions = [
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
     * List of HTML item templates
     *
     * @var array
     */
    protected $templates = [
        'url' => '<a href="%2$s" class="cgit-breadcrumb-item link">%1$s</a>',
        'current' => '<span class="cgit-breadcrumb-item current">%s</span>',
        'span' => '<span class="cgit-breadcrumb-item span">%s</span>',
    ];

    /**
     * Constructor
     *
     * Assign the default names for the home page and posts index, add the home
     * page to the list of items, and update the breadcrumb list to include any
     * other items.
     *
     * @param array $names
     * @return void
     */
    public function __construct($names = [])
    {
        $this->names = wp_parse_args($names, [
            'home' => 'Home',
            'index' => 'Posts',
        ]);

        // Apply WordPress filters
        $this->names = apply_filters('cgit_breadcrumb_names', $this->names);
        $this->templates = apply_filters(
            'cgit_breadcrumb_templates',
            $this->templates
        );

        $this->add($this->names['home'], home_url('/'));
        $this->update();
    }

    /**
     * Append a breadcrumb item to the list
     *
     * @param string $text
     * @param mixed $url
     * @param boolean $current
     */
    protected function add($text, $url = false, $current = false)
    {
        $this->items[] = [
            'text' => $text,
            'url' => $url,
            'current' => $current,
        ];
    }

    /**
     * Update the breadcrumb list
     *
     * Run each WordPress conditional function in turn until we can work out
     * what sort of page we are viewing. Then add or amend the items in the
     * breadcrumb list accordingly.
     *
     * @return void
     */
    protected function update()
    {
        foreach ($this->conditions as $condition) {
            if (!$condition()) {
                continue;
            }

            $method = lcfirst(str_replace('_', '', ucwords($condition, '_')));
            $this->$method();

            break;
        }
    }

    /**
     * Return breadcrumb items as an array
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Return breadcrumb links as an array
     *
     * Uses sprintf and the array of templates to generate HTML links or spans
     * from each of the breadcrumb items.
     *
     * @return array
     */
    public function getLinks()
    {
        return array_map(function ($item) {
            foreach ($this->templates as $key => $template) {
                if (isset($item[$key]) && $item[$key]) {
                    return sprintf($template, $item['text'], $item['url']);
                }

                return sprintf($this->templates['span'], $item['text']);
            }
        }, $this->items);
    }

    /**
     * Return HTML breadcrumb with separator
     *
     * @param string $sep
     * @return string
     */
    public function render($sep = ' / ')
    {
        return implode($sep, $this->getLinks());
    }

    /**
     * Return HTML breadcrumb list
     *
     * @param string $element
     * @return string
     */
    public function renderList($element = 'ol')
    {
        $items = array_map(function ($link) {
            return '<li>' . $link . '</li>';
        }, $this->getLinks());

        return "<$element>" . implode(PHP_EOL, $items) . "</$element>";
    }

    /**
     * Amend breadcrumb list: front page
     *
     * Removes the URL from the first (home page) breadcrumb item and marks it
     * as the current page.
     *
     * @return void
     */
    protected function isFrontPage()
    {
        $this->items[0]['url'] = false;
        $this->items[0]['current'] = true;
    }

    /**
     * Amend breadcrumb list: home page (posts index)
     *
     * If the site is set to use a static front page, add the posts index page
     * to the breadcrumb list. Otherwise, run the front page method.
     *
     * @return void
     */
    protected function isHome()
    {
        if (get_option('show_on_front') == 'page') {
            return $this->add($this->names['index'], false, true);
        }

        $this->isFrontPage();
    }

    /**
     * Amend breadcrumb list: page
     *
     * A standard page may be part of a hierarchy. Assembles a list of ancestor
     * pages, reverses them, and adds them in turn. Then adds the current page
     * to the breadcrumb.
     *
     * @return void
     */
    protected function isPage()
    {
        global $post;

        $parent_id = $post->post_parent;
        $ancestors = [];

        // Assemble a list of ancestor pages
        if ($parent_id) {
            while ($parent_id) {
                $parent_post = get_post($parent_id);
                $parent_id = $parent_post->post_parent;
                $ancestors[] = $parent_post;
            }
        }

        // Reverse the order of the ancestor pages
        $ancestors = array_reverse($ancestors);

        // Add each ancestor page to the breadcrumb list
        foreach ($ancestors as $ancestor) {
            $this->add(get_the_title($ancestor), get_permalink($ancestor));
        }

        // Add the current page to the list
        $this->add(get_the_title(), false, true);
    }

    /**
     * Amend breadcrumb list: singular post or custom post type
     *
     * @return void
     */
    protected function isSingular()
    {
        global $post;

        $type = get_post_type($post);

        // If this is a standard post and the site is using a static front page,
        // add the posts index page to the list.
        if ($type == 'post' && get_option('show_on_front') == 'page') {
            $this->add(
                $this->names['index'],
                get_permalink(get_option('page_for_posts'))
            );
        }

        // If this is not a standard post, add the custom post type archive URL
        // to the list.
        if ($type != 'post') {
            $type_object = get_post_type_object($type);
            $this->add(
                $type_object->labels->name,
                get_post_type_archive_link($type)
            );
        }

        // Add the post itself to the breadcrumb list
        $this->add(get_the_title(), false, true);
    }

    /**
     * Amend breadcrumb list: category
     *
     * @return void
     */
    protected function isCategory()
    {
        $this->add('Category');
        $this->add(single_cat_title('', false), false, true);
    }

    /**
     * Amend breadcrumb list: tag
     *
     * @return void
     */
    protected function isTag()
    {
        $this->add('Tag');
        $this->add(single_tag_title('', false), false, true);
    }

    /**
     * Amend breadcrumb list: custom taxonomy
     *
     * @return void
     */
    protected function isTax()
    {
        $taxonomy = get_taxonomy(get_query_var('taxonomy'));
        $term = get_term_by('slug', get_query_var('term'), $taxonomy->name);

        $this->add($taxonomy->labels->name);
        $this->add($term->name, false, true);
    }

    /**
     * Amend breadcrumb list: search results
     *
     * @return void
     */
    protected function isSearch()
    {
        $this->add('Search results', false, true);
    }

    /**
     * Amend breadcrumb list: day archive
     *
     * @return void
     */
    public function isDay()
    {
        $this->add(get_the_date(), false, true);
    }

    /**
     * Amend breadcrumb list: month archive
     *
     * @return void
     */
    protected function isMonth()
    {
        $this->add(get_the_date('F Y'), false, true);
    }

    /**
     * Amend breadcrumb list: year archive
     *
     * @return void
     */
    protected function isYear()
    {
        $this->add(get_the_date('Y'), false, true);
    }

    /**
     * Amend breadcrumb list: custom post type archive
     *
     * @return void
     */
    protected function isPostTypeArchive()
    {
        $this->add(post_type_archive_title('', false), false, true);
    }

    /**
     * Amend breadcrumb list: archive
     *
     * @return void
     */
    protected function isArchive()
    {
        $this->add('Archive', false, true);
    }

    /**
     * Amend breadcrumb list: 404 page
     *
     * @return void
     */
    protected function is404()
    {
        $this->add('Page not found', false, true);
    }
}
