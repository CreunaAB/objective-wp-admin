<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Fields\EditorToolbar;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EditorToolbarSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EditorToolbar::class);
    }

    function it_contains_a_tinymce_toolbar_config()
    {
        $this->config()->shouldBe('');
    }

    function it_can_add_a_bold_button()
    {
        $this->bold()->config()->shouldBe('bold');
    }
}
