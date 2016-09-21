<?php

namespace Creuna\ObjectiveWpAdmin\PostTypes;

class PostTypeBuilder
{
    protected $name;
    protected $options;

    public function __construct($name, array $options)
    {
        $this->name = $name;
        $this->options = $options;
    }

    public static function make($name)
    {
        return (new static($name, []))->reset();
    }

    /**
     * Gets the resulting post type options.
     *
     * @return array
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Build the PostType data structure.
     *
     * @return PostType
     */
    public function get()
    {
        return new PostType($this->name, $this->options());
    }

    /**
     * Reset the options to that of an empty post type,
     * basing the basic labels on the post type name.
     *
     * @return self
     */
    public function reset()
    {
        return new static($this->name, [
            'labels' => [
                'name' => ucwords($this->name),
                'singular_name' => ucwords(preg_replace('/s$/', '', $this->name)),
            ],
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'has_archive' => true,
            'supports' => [
                'title' => false,
                'editor' => false,
                'author' => false,
                'thumbnail' => false,
                'excerpt' => false,
                'trackbacks' => false,
                'custom-fields' => false,
                'comments' => false,
                'revisions' => false,
                'page-attributes' => false,
                'post-formats' => false,
            ],
        ]);
    }

    /**
     * Sets the labels of the post type.
     *
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#labels
     * @return self
     */
    public function labels($labels)
    {
        return new static($this->name, array_merge_recursive($this->options, [
            'labels' => $labels
        ]));
    }
}
