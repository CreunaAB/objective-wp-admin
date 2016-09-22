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
}
