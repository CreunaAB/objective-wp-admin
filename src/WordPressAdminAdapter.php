<?php

namespace Creuna\ObjectiveWpAdmin;

use add_action;
use add_filter;
use register_post_type;

class WordPressAdminAdapter implements AdminAdapter
{
    public function action($hook, callable $action, $priority)
    {
        add_action($hook, $action, $priority);
    }

    public function filter($hook, callable $filter, $priority)
    {
        add_filter($hook, $filter, $priority);
    }

    public function registerPostType($name, array $options)
    {
        register_post_type($name, $options);
    }
}
