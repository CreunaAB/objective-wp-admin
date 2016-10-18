<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Repository;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Creuna\ObjectiveWpAdmin\Util\DynamicObject;

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
                'post_title' => 'Some Title',
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
            new DynamicObject([
                'id' => 1,
                'slug' => 'some-slug',
                'title' => 'Some Title',
                'createdAt' => new DateTime('2011-11-11 00:00:00'),
                'updatedAt' => new DateTime('2011-11-11 00:00:00'),
                'status' => 'publish',
                'someField' => 'value',
            ]),
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

    function it_can_make_where_clauses(AdminAdapter $adapter)
    {
        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'field',
                    'value' => 'value',
                    'compare' => '=',
                ],
                [
                    'key' => 'other',
                    'value' => 123,
                    'compare' => '>=',
                ],
            ]
        ])->shouldBeCalled()->willReturn([]);

        $this
            ->where('field', '=', 'value')
            ->where('other', '>=', 123)
            ->all()->shouldReturn([]);
    }

    function it_treats_a_constraint_on_slug_differently(AdminAdapter $adapter)
    {
        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype',
            'meta_query' => [ 'relation' => 'AND' ],
            'name' => 'some-slug',
        ])->shouldBeCalled()->willReturn([]);

        $this
            ->where('slug', '=', 'some-slug')
            ->all()->shouldReturn([]);
    }

    function it_can_get_posts_before_a_specific_date(AdminAdapter $adapter)
    {
        $date = new DateTime();

        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype',
            'date_query' => [
                'before' => $date->format(DateTime::ISO8601),
            ],
        ])->shouldBeCalled()->willReturn([]);

        $this->before($date)->all()->shouldBe([]);
    }

    function it_can_get_posts_after_a_specific_date(AdminAdapter $adapter)
    {
        $date = new DateTime();

        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype',
            'date_query' => [
                'after' => $date->format(DateTime::ISO8601),
            ],
        ])->shouldBeCalled()->willReturn([]);

        $this->after($date)->all()->shouldBe([]);
    }

    function it_can_get_posts_from_within_a_specific_time_span(AdminAdapter $adapter)
    {
        $date = new DateTime();

        $adapter->getPosts([
            'posts_per_page' => -1,
            'offset' => 0,
            'post_type' => 'spec_creuna_objectivewpadmin_persistance_myposttype',
            'date_query' => [
                'before' => $date->format(DateTime::ISO8601),
                'after' => $date->format(DateTime::ISO8601),
            ],
        ])->shouldBeCalled()->willReturn([]);

        $this->before($date)->after($date)->all()->shouldBe([]);
    }
}

class MyPostType implements PostType
{
    public function describe(Schema $schema)
    {
        $schema->title();
        $schema->string('someField');
    }
}
