<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistence;

use Creuna\ObjectiveWpAdmin\Persistence\PostType;
use Creuna\ObjectiveWpAdmin\Persistence\PostTypeUtils;
use Creuna\ObjectiveWpAdmin\Persistence\Schema;
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

    function it_trims_the_slug_to_be_no_more_than_20_chars()
    {
        $this->myName()->shouldBe('istence_testposttype');
    }
}

class TestPostType implements PostType
{
    public function describe(Schema $schema)
    {
        $schema->string('name');
    }

    public function myName()
    {
        return PostTypeUtils::postTypeName($this);
    }
}
