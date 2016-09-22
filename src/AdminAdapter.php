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
     */
    public function action($hook, callable $callback, $priority);

    /**
     * Adds a filter hook to the system.
     *
     * @param string   $hook     The id of the hook.
     * @param callback $callback The listener.
     * @param int      $priority The priority of the hook.
     */
    public function filter($hook, callable $callback, $priority);

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
     * @param int    $id  The post id.
     * @param string $key The meta key.
     */
    public function getPostMeta($id, $key);
}
