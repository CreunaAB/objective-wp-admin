<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Repository;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function let(AdminAdapter $adapter)
    {
        $this->beConstructedWith($adapter, new MyPostType);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_gets_all_posts_of_the_post_type(AdminAdapter $adapter)
    {
        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype'
        ])->shouldBeCalled()->willReturn([]);

        $this->all()->shouldReturn([]);
    }

    function it_wraps_the_posts_from_get_posts(AdminAdapter $adapter)
    {
        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype'
        ])->shouldBeCalled()->willReturn([
            (object) [
                'ID' => 1,
                'post_author' => 1,
                'post_name' => 'some-slug',
                'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype',
                'post_title' => '',
                'post_date' => '2011-11-11 00:00:00',
                'post_date_gmt' => '2011-11-11 00:00:00',
                'post_content' => '',
                'post_excerpt' => '',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_password' => '',
                'post_parent' => 0,
                'post_modified' => '2011-11-11 00:00:00',
                'post_modified_gmt' => '2011-11-11 00:00:00',
                'comment_count' => 0,
                'menu_order' => 0,
            ],
        ]);

        $adapter->getPostMeta(1, 'someField', true)->shouldBeCalled()->willReturn('value');

        $this->all()->shouldBeLike([
            (object) [
                'id' => 1,
                'slug' => 'some-slug',
                'createdAt' => new DateTime('2011-11-11 00:00:00'),
                'updatedAt' => new DateTime('2011-11-11 00:00:00'),
                'status' => 'publish',
                'someField' => 'value',
            ],
        ]);
    }

    function it_can_take_a_few_posts(AdminAdapter $adapter)
    {
        $adapter->getPosts([
            'posts_per_page' => 10,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype'
        ])->shouldBeCalled()->willReturn([]);

        $this->take(10)->all()->shouldBe([]);
    }

    function it_can_skip_a_few_posts(AdminAdapter $adapter)
    {
        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 10,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype'
        ])->shouldBeCalled()->willReturn([]);

        $this->skip(10)->all()->shouldBe([]);
    }
}

class MyPostType implements PostType
{
    public function describe(Schema $schema)
    {
        $schema->string('someField');
    }
}