<?php

namespace Creuna\ObjectiveWpAdmin;

use add_action;
use add_filter;
use remove_menu_page;
use remove_submenu_page;

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
}
