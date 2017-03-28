<?php

namespace spec\Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Admin;
use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Event;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Pages\Page;
use Creuna\ObjectiveWpAdmin\Persistence\PostType;
use Creuna\ObjectiveWpAdmin\Persistence\PostTypeUtils;
use Creuna\ObjectiveWpAdmin\Persistence\Repository;
use Creuna\ObjectiveWpAdmin\Persistence\Schema;
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
            }), 1000, 0
        )->shouldHaveBeenCalled();
    }

    function it_works_with_filters_too(AdminAdapter $adapter)
    {
        $this->hook(new TestFilter);

        $this->execute();

        $adapter->filter(
            'filter_name', Argument::that(function ($callback) {
                return $callback() == 'result of calling TestFilter::call';
            }), 1000, 0
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
                    'menu_icon' => 'dashicons-admin-post',
                ]
            )->shouldBeCalled();

            $callback();

            return true;
        }), 1000, 0)->shouldBeCalled();
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
            1000,
            1
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
            1000,
            3
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
            1000,
            0
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
            1000,
            4
        )->shouldBeCalled();
    }

    private function postTypeCustomizeEditorFilterShouldBeAdded(AdminAdapter $adapter)
    {
        $adapter->filter(
            'wp_editor_settings',
            Argument::any(),
            1000,
            2
        )->shouldBeCalled();
    }

    function it_creates_repositories_for_post_types(AdminAdapter $adapter)
    {
        $this->repository(Test::class)->shouldHaveType(Repository::class);
    }

    function it_can_get_the_type_of_a_post()
    {
        $post = (object) [
            '_type' => Test::class,
        ];

        $this->typeOf($post)->shouldBe(Test::class);
    }

    function it_can_get_the_type_of_a_wp_post()
    {
        $this->registerType(Test::class);
        require_once(__DIR__.'/WPPostShim.php');
        $post = new \WP_Post;
        $post->post_type = PostTypeUtils::postTypeName(new Test);

        $this->typeOf($post)->shouldBe(Test::class);
    }

    function it_registers_a_static_page(AdminAdapter $adapter)
    {
        $page = new TestPage;
        $this->registerPage($page);

        $adapter->action(
            'admin_menu',
            Argument::that(function ($callback) use ($adapter, $page) {
                $adapter->addMenuPage(
                    'Test Page',
                    'Test Page',
                    'manage_options',
                    'tivewpadmin_testpage',
                    Argument::that(function ($render) use ($page) {
                        $_GET = ['key' => 'value', 'method' => 'GET'];
                        $_POST = ['key' => 'value', 'method' => 'POST'];

                        $_SERVER['REQUEST_METHOD'] = 'GET';

                        ob_start();
                        $render();
                        $whenGet = ob_get_clean();
                        $worksWithGet = $whenGet === $page->get($_GET);

                        $_SERVER['REQUEST_METHOD'] = 'POST';

                        ob_start();
                        $render();
                        $whenPost = ob_get_clean();
                        $worksWithPost = $whenPost === $page->post($_POST);

                        return $worksWithGet && $worksWithPost;
                    })
                )->shouldBeCalled();
                $callback();
                return true;
            }),
            1000,
            0
        )->shouldBeCalled();

        $this->execute();
    }
}

class TestPage implements Page
{
    public function title()
    {
        return 'Test Page';
    }

    public function get($query)
    {
        return json_encode($query);
    }

    public function post($form)
    {
        return json_encode($form);
    }
}

class TestAction implements Action
{
    public function event()
    {
        return Event::init();
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
        return new Event('filter_name', 0);
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
