<?php

namespace Creuna\ObjectiveWpAdmin;

interface AdminAdapter
{
    /**
     * Adds an action hook.
     *
     * @param string   $hook     The id of the hook.
     * @param callable $action   The action to be performed.
     * @param number   $priority The priority of the hook.
     */
    public function action($hook, callable $action, $priority);

    /**
     * Adds a filter hook.
     *
     * @param string   $hook     The id of the hook.
     * @param callable $filter   The filter.
     * @param number   $priority The priority of the hook.
     */
    public function filter($hook, callable $filter, $priority);

    /**
     * Registers a new post type.
     *
     * @param string $name    The name of the type (think database table name).
     * @param array  $options WordPress-specific options in an array.
     */
    public function registerPostType($name, array $options);
}
