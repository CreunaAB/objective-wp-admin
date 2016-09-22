<?php

namespace Creuna\ObjectiveWpAdmin\Reset;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;

class ResetToolbarAction implements Action
{
    public function event()
    {
        return 'admin_bar_menu';
    }

    public function call(AdminAdapter $admin, array $args)
    {
        list($toolbar) = $args;

        $toolbar->remove_node('about');
        $toolbar->remove_node('appearance');
        $toolbar->remove_node('comments');
        $toolbar->remove_node('customize');
        $toolbar->remove_node('dashboard');
        $toolbar->remove_node('documentation');
        $toolbar->remove_node('feedback');
        $toolbar->remove_node('menu-toggle');
        $toolbar->remove_node('new-content');
        $toolbar->remove_node('new-media');
        $toolbar->remove_node('new-page');
        $toolbar->remove_node('new-post');
        $toolbar->remove_node('new-user');
        $toolbar->remove_node('search');
        $toolbar->remove_node('support-forums');
        $toolbar->remove_node('themes');
        $toolbar->remove_node('view');
        $toolbar->remove_node('view-site');
        $toolbar->remove_node('wp-logo');
        $toolbar->remove_node('wp-logo-external');
        $toolbar->remove_node('wporg');

        // Default nodes still visible
        // $toolbar->remove_node('edit-profile');
        // $toolbar->remove_node('logout');
        // $toolbar->remove_node('my-account');
        // $toolbar->remove_node('site-name');
        // $toolbar->remove_node('top-secondary');
        // $toolbar->remove_node('user-actions');
        // $toolbar->remove_node('user-info');
    }
}
