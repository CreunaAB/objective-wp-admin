<?php

namespace Creuna\ObjectiveWpAdmin;

use add_action;
use add_filter;
use remove_menu_page;
use remove_submenu_page;
use register_post_type;
use get_post_meta;

class WordPressAdminAdapter implements AdminAdapter
{
    public function action($hook, callable $callback, $priority)
    {
        add_action($hook, $callback, $priority);
    }

    public function filter($hook, callable $callback, $priority)
    {
        add_filter($hook, $callback, $priority);
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
}
