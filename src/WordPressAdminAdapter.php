<?php

namespace Creuna\ObjectiveWpAdmin;

use add_action;
use add_filter;
use apply_filters;
use remove_menu_page;
use remove_submenu_page;
use register_post_type;
use get_post_meta;
use update_post_meta;
use get_post;
use get_posts;
use add_permastruct;
use wp_register_script;
use wp_enqueue_script;
use wp_register_style;
use wp_enqueue_style;

class WordPressAdminAdapter implements AdminAdapter
{
    public function action($hook, callable $callback, $priority, $arity)
    {
        add_action($hook, $callback, $priority, $arity);
    }

    public function filter($hook, callable $callback, $priority, $arity)
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
}
