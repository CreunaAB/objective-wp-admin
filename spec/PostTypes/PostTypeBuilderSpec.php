<?php

namespace spec\Creuna\ObjectiveWpAdmin\PostTypes;

use Creuna\ObjectiveWpAdmin\PostTypes\PostTypeBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PostTypeBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('things', []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PostTypeBuilder::class);
    }

    function it_can_be_set_to_an_empty_post_type()
    {
        $this->reset()->options()->shouldReturn([
            'labels' => [
                'name' => 'Things',
                'singular_name' => 'Thing',
            ],
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'has_archive' => true,
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
        ]);
    }

    function it_can_set_labels()
    {
        $labels = [
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new' => null,
            'add_new_item' => null,
            'edit_item' => null,
            'new_item' => null,
            'view_item' => null,
            'search_items' => null,
            'not_found' => null,
            'not_found_in_trash' => null,
            'parent_item_colon' => null,
            'all_items' => null,
            'archives' => null,
            'insert_into_item' => null,
            'uploaded_to_this_item' => null,
            'featured_image' => null,
            'set_featured_image' => null,
            'remove_featured_image' => null,
            'use_featured_image' => null,
            'menu_name' => null,
            'filter_items_list' => null,
            'items_list_navigation' => null,
            'items_list' => null,
            'name_admin_bar' => null,
        ];

        $this->labels($labels)->options()->shouldReturn([
            'labels' => $labels,
        ]);
    }
}
