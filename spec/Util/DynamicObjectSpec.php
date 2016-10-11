<?php

namespace spec\Creuna\ObjectiveWpAdmin\Util;

use Creuna\ObjectiveWpAdmin\Util\DynamicObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DynamicObjectSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DynamicObject::class);
    }

    function it_is_created_with_fields()
    {
        $this->beConstructedWith([
            'field' => 'value',
            'otherField' => 123,
        ]);

        $this->field->shouldBe('value');
        $this->otherField->shouldBe(123);
    }

    function it_is_created_with_methods()
    {
        $this->beConstructedWith([
            'method' => function ($a, $b) {
                return [$b, $a];
            }
        ]);

        $this->method(1, 'hello')->shouldBe(['hello', 1]);
    }
}
