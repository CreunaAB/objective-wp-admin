<?php

namespace spec\Creuna\ObjectiveWpAdmin\Reset;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Reset\ResetToolbarAction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResetToolbarActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResetToolbarAction::class);
    }

    function it_removes_nodes_from_the_toolbar(AdminAdapter $adapter, WpToolbar $toolbar)
    {
        $this->call($adapter, [$toolbar]);

        $toolbar->remove_node('about')->shouldHaveBeenCalled();
        $toolbar->remove_node('appearance')->shouldHaveBeenCalled();
        $toolbar->remove_node('comments')->shouldHaveBeenCalled();
        $toolbar->remove_node('customize')->shouldHaveBeenCalled();
        $toolbar->remove_node('dashboard')->shouldHaveBeenCalled();
        $toolbar->remove_node('documentation')->shouldHaveBeenCalled();
        $toolbar->remove_node('feedback')->shouldHaveBeenCalled();
        $toolbar->remove_node('new-content')->shouldHaveBeenCalled();
        $toolbar->remove_node('new-media')->shouldHaveBeenCalled();
        $toolbar->remove_node('new-page')->shouldHaveBeenCalled();
        $toolbar->remove_node('new-post')->shouldHaveBeenCalled();
        $toolbar->remove_node('new-user')->shouldHaveBeenCalled();
        $toolbar->remove_node('search')->shouldHaveBeenCalled();
        $toolbar->remove_node('support-forums')->shouldHaveBeenCalled();
        $toolbar->remove_node('themes')->shouldHaveBeenCalled();
        $toolbar->remove_node('view')->shouldHaveBeenCalled();
        $toolbar->remove_node('view-site')->shouldHaveBeenCalled();
        $toolbar->remove_node('wp-logo')->shouldHaveBeenCalled();
        $toolbar->remove_node('wp-logo-external')->shouldHaveBeenCalled();
        $toolbar->remove_node('wporg')->shouldHaveBeenCalled();
    }
}

interface WpToolbar
{
    public function remove_node($node);
}
