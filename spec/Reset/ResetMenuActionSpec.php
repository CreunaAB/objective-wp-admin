<?php

namespace spec\Creuna\ObjectiveWpAdmin\Reset;

use Creuna\ObjectiveWpAdmin\Reset\ResetMenuAction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Creuna\ObjectiveWpAdmin\AdminAdapter;

class ResetMenuActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResetMenuAction::class);
    }

    function it_removes_menu_items(AdminAdapter $adapter)
    {
        $this->call($adapter, []);

        $adapter->removeMenuPage('edit.php')->shouldHaveBeenCalled();
        $adapter->removeMenuPage('edit.php?post_type=page')->shouldHaveBeenCalled();
        $adapter->removeMenuPage('edit-comments.php')->shouldHaveBeenCalled();
        $adapter->removeMenuPage('themes.php')->shouldHaveBeenCalled();
        $adapter->removeMenuPage('options-general.php')->shouldHaveBeenCalled();
        $adapter->removeSubMenuPage('plugins.php', 'plugin-editor.php')->shouldHaveBeenCalled();
    }
}
