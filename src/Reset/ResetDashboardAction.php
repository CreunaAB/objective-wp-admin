<?php

namespace Creuna\ObjectiveWpAdmin\Reset;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;

class ResetDashboardAction implements Action
{
    public function event()
    {
        return 'wp_dashboard_setup';
    }

    public function call(AdminAdapter $admin, array $args)
    {
        global $wp_meta_boxes;

        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    }
}
