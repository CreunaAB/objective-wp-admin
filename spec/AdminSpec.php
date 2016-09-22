<?php

namespace spec\Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Admin;
use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use StdClass;

class AdminSpec extends ObjectBehavior
{
    function let(AdminAdapter $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Admin::class);
    }

    function it_hooks_into_the_underlying_system(AdminAdapter $adapter)
    {
        $this->hook(new TestAction);

        $this->execute();

        $adapter->action(
            'init', Argument::that(function ($callback) {
                return $callback() == 'result of calling TestAction::call';
            }), 1000
        )->shouldHaveBeenCalled();
    }

    function it_works_with_filters_too(AdminAdapter $adapter)
    {
        $this->hook(new TestFilter);

        $this->execute();

        $adapter->filter(
            'filter_name', Argument::that(function ($callback) {
                return $callback() == 'result of calling TestFilter::call';
            }), 1000
        )->shouldHaveBeenCalled();
    }

    function it_can_add_post_types(AdminAdapter $adapter)
    {
        $this->registerType(Test::class);

        $this->execute();

        $adapter->action('init', Argument::that(function ($callback) use ($adapter) {
            $callback();

            $adapter->registerPostType(
                strtolower(Test::class),
                [
                    'labels' => [
                        'name' => 'Tests',
                        'singular_name' => 'Test',
                    ],
                    'public' => true,
                    'supports' => [
                        'title' => false,
                        'editor' => false,
                        'author' => false,
                        'thumbnail' => false,
                        'excerpt' => false,
                        'trackbacks' => false,
                        'custom-fields' => false,
                        'comments' => false,
                        'revisions' => false,
                        'page-attributes' => false,
                        'post-formats' => false,
                    ],
                ]
            )->shouldHaveBeenCalled();
        }), 1000);

        $adapter->getPostMeta(1, 'field_name')
            ->shouldBeCalled()
            ->willReturn('xyz');

        $adapter->action(
            'edit_form_after_editor',
            Argument::that(function ($callback) {
                ob_start();
                $post = new StdClass;
                $post->ID = 1;
                $callback($post);
                $markup = ob_get_clean();

                return trim($markup) === trim("
                    <input name='field_name' value='xyz'>
                ");
            }),
            1000
        )->shouldHaveBeenCalled();
    }
}

class TestAction implements Action
{
    public function event()
    {
        return 'init';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        return 'result of calling TestAction::call';
    }
}

class TestFilter implements Filter
{
    public function event()
    {
        return 'filter_name';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        return 'result of calling TestFilter::call';
    }
}

class Test implements PostType
{
    public function describe(Schema $schema)
    {
        $schema->string('field_name');
    }
}
