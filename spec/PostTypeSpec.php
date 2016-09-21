<?php

namespace spec\Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Actions\AddPostTypeAction;
use Creuna\ObjectiveWpAdmin\Admin;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\PostType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PostTypeSpec extends ObjectBehavior
{
    function it_registers_an_empty_post_type(Admin $admin)
    {
        $this->beConstructedWith($admin, 'empty');

        $this->register();

        $admin->hook(Argument::type(AddPostTypeAction::class))->shouldHaveBeenCalled();
    }
}
