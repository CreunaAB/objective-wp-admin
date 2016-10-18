<?php

namespace Creuna\ObjectiveWpAdmin\Reset;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;

class ResetMenuAction implements Action
{
    public function event()
    {
        return 'admin_menu';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        // Left in the menu
        // $adapter->removeMenuPage('index.php');                         // Dashboard
        // $adapter->removeMenuPage('upload.php');                        // Media
        // $adapter->removeMenuPage('plugins.php');                       // Plugins
        // $adapter->removeMenuPage('users.php');                         // Users
        // $adapter->removeMenuPage('tools.php');                         // Tools

        // Removed from the menu
        $adapter->removeMenuPage('edit.php');                             // Posts
        $adapter->removeMenuPage('edit.php?post_type=page');              // Pages
        $adapter->removeMenuPage('edit-comments.php');                    // Comments
        $adapter->removeMenuPage('themes.php');                           // Appearance
        $adapter->removeMenuPage('options-general.php');                  // Settings
        $adapter->removeSubMenuPage('plugins.php', 'plugin-editor.php');  // Plugins > Editor
    }
}
