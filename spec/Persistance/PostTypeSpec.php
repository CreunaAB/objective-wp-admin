<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PostTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(TestPostType::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PostType::class);
    }

    function it_describes_itself(Schema $schema)
    {
        $this->describe($schema);

        $schema->string('name')->shouldHaveBeenCalled();
    }
}

class TestPostType implements PostType
{
    public function describe(Schema $schema)
    {
        $schema->string('name');
    }
}
