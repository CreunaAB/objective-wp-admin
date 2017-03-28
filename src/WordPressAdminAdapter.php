<?php

namespace Creuna\ObjectiveWpAdmin;

use add_action;
use add_filter;
use add_menu_page;
use add_permastruct;
use apply_filters;
use get_option;
use get_post;
use get_post_meta;
use get_posts;
use register_post_type;
use remove_menu_page;
use remove_submenu_page;
use update_option;
use update_post_meta;
use wp_enqueue_script;
use wp_enqueue_style;
use wp_register_script;
use wp_register_style;

class WordPressAdminAdapter implements AdminAdapter
{
    public function action($hook, callable $callback, $priority, $arity = 0)
    {
        add_action($hook, $callback, $priority, $arity);
    }

    public function filter($hook, callable $callback, $priority, $arity = 0)
    {
        add_filter($hook, $callback, $priority, $arity);
    }

    public function applyFilters($hook, $variable)
    {
        return apply_filters($hook, $variable);
    }

    public function removeMenuPage($id)
    {
        remove_menu_page($id);
    }

    public function addMenuPage($pageTitle, $menuTitle, $capability, $id, $callback)
    {
        add_menu_page($pageTitle, $menuTitle, $capability, $id, $callback);
    }

    public function removeSubMenuPage($id, $subId)
    {
        remove_submenu_page($id, $subId);
    }

    public function registerPostType($name, array $args)
    {
        register_post_type($name, $args);
    }

    public function getPostMeta($id, $key, $single)
    {
        return get_post_meta($id, $key, $single);
    }

    public function setPostMeta($id, $key, $value)
    {
        update_post_meta($id, $key, $value);
    }

    public function getPost($id)
    {
        return get_post($id);
    }

    public function getPosts(array $query)
    {
        return get_posts($query);
    }

    public function addPermastruct($postType, $permastruct, array $args)
    {
        return add_permastruct($postType, $permastruct, $args);
    }

    public function registerScript($handle, $src, $deps = [], $version = null)
    {
        wp_register_script($handle, $src, $deps, $version ?: false, true);
    }

    public function enqueueScript($handle)
    {
        wp_enqueue_script($handle);
    }

    public function registerStyle($handle, $src, $deps = [], $version = null)
    {
        wp_register_style($handle, $src, $deps, $version ?: false, 'screen');
    }

    public function enqueueStyle($handle)
    {
        wp_enqueue_style($handle);
    }

    public function getOption($key, $default = null)
    {
        return get_option($key, $default);
    }

    public function setOption($key, $value, $autoload = false)
    {
        return update_option($key, $value, $autoload);
    }
}
