<?php

namespace Creuna\ObjectiveWpAdmin;

interface AdminAdapter
{
    /**
     * Adds an action hook to the system.
     *
     * @param string   $hook     The id of the hook.
     * @param callback $callback The listener.
     * @param int      $priority The priority of the hook.
     * @param int      $arity    The arity of the callback.
     */
    public function action($hook, callable $callback, $priority, $arity);

    /**
     * Adds a filter hook to the system.
     *
     * @param string   $hook     The id of the hook.
     * @param callback $callback The listener.
     * @param int      $priority The priority of the hook.
     * @param int      $arity    The arity of the callback.
     */
    public function filter($hook, callable $callback, $priority, $arity);

    /**
     * Applies a filter to some variable.
     *
     * @param string $hook     The id of the filter.
     * @param string $variable The variable to transform.
     *
     * @return mixed
     */
    public function applyFilters($hook, $variable);

    /**
     * Removes a menu page.
     *
     * @see src/Reset/ResetMenuHook.php
     *
     * @param string $id The identifier of the menu page. Usually it's the
     *                   PHP file that the menu item points to.
     */
    public function removeMenuPage($id);

    /**
     * Removes a sub menu page.
     *
     * @see src/Reset/ResetMenuHook.php
     *
     * @param string $id    The identifier of the parent menu page. Usually it's the
     *                      PHP file that the parent menu item points to.
     * @param string $subId The identifier of the sub menu page. Usually it's the
     *                      PHP file that the sub menu item points to.
     */
    public function removeSubMenuPage($id, $subId);

    /**
     * Registers a post type.
     *
     * @param string $name The name of the post type.
     * @param array  $args The configuration of the type.
     */
    public function registerPostType($name, array $args);

    /**
     * Gets the meta value of a post for a specific meta key.
     *
     * @param int    $id     The post id.
     * @param string $key    The meta key.
     * @param bool   $single Whether or not to return a single value.
     *
     * @return mixed
     */
    public function getPostMeta($id, $key, $single);

    /**
     * Sets or updates the value for a specific meta key on a post.
     *
     * @param int    $id    The post id.
     * @param string $key   The meta key.
     * @param string $value The new value.
     */
    public function setPostMeta($id, $key, $value);

    /**
     * Gets the post for a given id.
     *
     * @param int $id The post id.
     *
     * @return WP_Post
     */
    public function getPost($id);

    /**
     * Queries the database for posts.
     *
     * @see https://codex.wordpress.org/Template_Tags/get_posts
     *
     * @param array $query
     *
     * @return array
     */
    public function getPosts(array $query);

    /**
     * Registers a permalink structure for a post type.
     *
     * @see https://codex.wordpress.org/Function_Reference/add_permastruct
     *
     * @param string $postType    The name of the post type.
     * @param string $permastruct The permastruct expression, like "/%postname%".
     * $param array  $args        Some arguments, see docs.
     */
    public function addPermastruct($postType, $permastruct, array $args);

    /**
     * Registers a JavaScript file, identified by an id.
     *
     * @see https://developer.wordpress.org/reference/functions/wp_register_script
     *
     * @param string $id      A unique id for this script.
     * @param string $src     The source URL of the script.
     * @param array  $deps    A list of ids for all other scripts that this one depends on.
     * @param string $version A version number to be used as a cache buster.
     */
    public function registerScript($id, $src, $deps = [], $version = null);

    /**
     * Includes a JavaScript file on the page, identified by an id.
     *
     * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script
     *
     * @param string $id The unique id of the script to include on the page.
     */
    public function enqueueScript($id);

    /**
     * @see https://developer.wordpress.org/reference/functions/wp_register_style
     *
     * @param string $id      A unique id for this stylesheet.
     * @param string $src     The source URL of the stylesheet.
     * @param array  $deps    A list of ids for all other stylesheets that this one depends on.
     * @param string $version A version number to be used as a cache buster.
     */
    public function registerStyle($id, $src, $deps = [], $version = null);

    /**
     * Includes a CSS stylesheet on the page, identified by an id.
     *
     * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style
     *
     * @param string $id The unique id of the stylesheet to include on the page.
     */
    public function enqueueStyle($id);
}
