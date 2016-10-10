<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\Persistance\Field;
use Creuna\ObjectiveWpAdmin\Persistance\Fields\StringField;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SchemaSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Schema::class);
    }

    function it_outputs_an_array()
    {
        $this->fields()->shouldBe([]);
    }

    function it_creates_fields()
    {
        $this->string('name');

        $this->fields()->shouldBeLike([
            new StringField('name', 'Name', false)
        ]);
    }

    function it_can_add_modifiers_to_fields()
    {
        $this->string('one')->required();
        $this->string('pol', 'Pöl');

        $this->fields()->shouldBeLike([
            new StringField('one', 'One', true),
            new StringField('pol', 'Pöl', false),
        ]);
    }

    function it_contains_the_wp_post_type_supports_array()
    {
        $this->supports()->shouldBe([]);
    }

    function it_can_change_the_supports_fields()
    {
        $this->support('title');

        $this->supports()->shouldContain('title');
    }

    function it_has_a_shorthand_for_enabling_title()
    {
        $this->title();

        $this->supports()->shouldContain('title');
    }

    function it_has_a_shorthand_for_enabling_body()
    {
        $this->body();

        $this->supports()->shouldContain('editor');
    }

    function it_contains_the_wp_permalink_structure()
    {
        $this->permastruct()->shouldBe('/:id');
        $this->permastruct('/my-path/:id');
        $this->permastruct()->shouldBe('/my-path/:id');
    }

    function it_fixes_trailing_or_non_leading_slashes()
    {
        $this->permastruct('my-path/:id/');
        $this->permastruct()->shouldBe('/my-path/:id');
    }
}
