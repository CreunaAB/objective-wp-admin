<?php

namespace spec\Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Admin;
use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Repository;
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
        $this->postTypeRegisterActionShouldBeAdded($adapter);
        $this->postTypeEditPageActionShouldBeAdded($adapter);
        $this->postTypeSaveActionShouldBeAdded($adapter);
        $this->postTypePermalinkActionShouldBeAdded($adapter);
        $this->postTypePermalinkFilterShouldBeAdded($adapter);
        $this->postTypeCustomizeEditorFilterShouldBeAdded($adapter);

        $this->registerType(Test::class);
        $this->execute();
    }

    private function postTypeRegisterActionShouldBeAdded(AdminAdapter $adapter)
    {
        $adapter->action('init', Argument::that(function ($callback) use ($adapter) {
            $adapter->registerPostType(
                'bjectivewpadmin_test',
                [
                    'labels' => [
                        'name' => 'Tests',
                        'singular_name' => 'Test',
                        'add_new_item' => 'Add New Test',
                        'edit_item' => 'Edit Test',
                        'new_item' => 'New Test',
                        'view_item' => 'View Test',
                        'search_items' => 'Search Tests',
                        'not_found' => 'No tests found',
                        'not_found_in_trash' => 'No tests found in trash',
                        'all_items' => 'All Tests',
                        'archives' => 'Test Archives',
                        'insert_into_item' => 'Insert into test',
                        'uploaded_to_this_item' => 'Uploaded to this test',
                    ],
                    'public' => true,
                    'supports' => false,
                    'rewrite' => [
                        'with_front' => false,
                        'slug' => 'bjectivewpadmin_test',
                        'feeds' => false,
                        'pages' => false,
                    ],
                ]
            )->shouldBeCalled();

            $callback();

            return true;
        }), 1000)->shouldBeCalled();
    }

    private function postTypeEditPageActionShouldBeAdded(AdminAdapter $adapter)
    {
        $adapter->getPostMeta(1, 'fieldName', true)
            ->shouldBeCalled()
            ->willReturn('xyz');

        $adapter->action(
            'edit_form_after_editor',
            Argument::that(function ($callback) {
                ob_start();
                $post = new StdClass;
                $post->ID = 1;
                $post->post_type = 'bjectivewpadmin_test';
                $callback($post);
                $markup = ob_get_clean();

                return strpos($markup,
                    "name='custom_fieldName'"
                ) !== false && strpos($markup,
                    "value='xyz'"
                ) !== false;
            }),
            1000
        )->shouldBeCalled();
    }

    private function postTypeSaveActionShouldBeAdded(AdminAdapter $adapter)
    {
        $adapter->action(
            'save_post',
            Argument::that(function ($callback) use ($adapter) {
                $_POST['custom_fieldName'] = 'value';
                $post = new StdClass;
                $post->ID = 1;
                $post->post_type = 'bjectivewpadmin_test';
                $adapter->getPost(1)->shouldBeCalled()->willReturn($post);
                $adapter->setPostMeta(1, 'fieldName', 'value')->shouldBeCalled();

                $callback(1);
                return true;
            }),
            1000
        )->shouldBeCalled();
    }

    private function postTypePermalinkActionShouldBeAdded(AdminAdapter $adapter)
    {
        $adapter->addPermastruct(
            'bjectivewpadmin_test',
            '/bjectivewpadmin_test/:id',
            [
                'with_front' => false,
                'feed' => false,
                'paged' => false,
            ]
        )->shouldBeCalled();

        $adapter->action(
            'wp_loaded',
            Argument::that(function ($callback) use ($adapter) {
                $callback();
                return true;
            }),
            1000
        )->shouldBeCalled();
    }

    private function postTypePermalinkFilterShouldBeAdded(AdminAdapter $adapter)
    {
        $adapter->getPostMeta(1, 'fieldName', true)
            ->shouldBeCalled()
            ->willReturn('xyz');

        $adapter->filter(
            'post_type_link',
            Argument::that(function ($callback) use ($adapter) {
                $post = (object) [
                    'ID' => 1,
                    'post_author' => 1,
                    'post_name' => 'some-slug',
                    'post_type' => 'bjectivewpadmin_test',
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
                ];

                $input = 'http://example.com/:id/:slug/:createdAt/:updatedAt/:status/:fieldName';
                $output = 'http://example.com/1/some-slug/2011-11-11/2011-11-11/publish/xyz';
                return $callback($input, $post, false) === $output;
            }),
            1000
        )->shouldBeCalled();
    }

    private function postTypeCustomizeEditorFilterShouldBeAdded(AdminAdapter $adapter)
    {
        $adapter->filter(
            'wp_editor_settings',
            Argument::any(),
            1000
        )->shouldBeCalled();
    }

    function it_creates_repositories_for_post_types(AdminAdapter $adapter)
    {
        $this->repository(Test::class)->shouldHaveType(Repository::class);
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
        $schema->string('fieldName');
    }
}
