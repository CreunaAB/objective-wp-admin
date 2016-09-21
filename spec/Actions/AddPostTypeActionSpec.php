<?php

namespace spec\Creuna\ObjectiveWpAdmin\Actions;

use Creuna\ObjectiveWpAdmin\Actions\AddPostTypeAction;
use Creuna\ObjectiveWpAdmin\AdminAdapter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddPostTypeActionSpec extends ObjectBehavior
{
    function it_can_create_a_completely_empty_post_type(AdminAdapter $admin)
    {
        $this->beConstructedWith('empty', []);

        $this->fire($admin);

        $admin->registerPostType('empty', [])->shouldHaveBeenCalled();
    }
}
