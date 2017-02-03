<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistence;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Icons\Dashicon;
use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\Fields\Editor;
use Creuna\ObjectiveWpAdmin\Persistence\Fields\StringField;
use Creuna\ObjectiveWpAdmin\Persistence\Schema;
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

    function it_contains_the_wp_post_type_supports_array()
    {
        $this->supports()->shouldBe([]);
    }

    function it_can_change_the_supports_fields()
    {
        $this->support('title');

        $this->supports()->shouldContain('title');
    }

    function it_has_a_shorthand_for_enabling_title()
    {
        $this->title();

        $this->supports()->shouldContain('title');
    }

    function it_has_a_shorthand_for_enabling_body(Editor $editor)
    {
        $this->body($editor);

        $this->supports()->shouldContain('editor');
        $this->bodyEditor()->shouldBe($editor);
    }

    function it_contains_the_wp_permalink_structure()
    {
        $this->permastruct()->shouldBe('/:id');
        $this->permastruct('/my-path/:id');
        $this->permastruct()->shouldBe('/my-path/:id');
    }

    function it_fixes_trailing_or_non_leading_slashes()
    {
        $this->permastruct('my-path/:id/');
        $this->permastruct()->shouldBe('/my-path/:id');
    }

    function it_contains_the_labels()
    {
        $this->labels()->shouldBe([]);
        $this->label('name', 'Names');
        $this->labels()->shouldBe([
            'name' => 'Names',
        ]);
        $this->labels([
            'new_item' => 'New Name',
            'singular_name' => 'Name',
        ]);
        $this->labels()->shouldBe([
            'name' => 'Names',
            'new_item' => 'New Name',
            'singular_name' => 'Name',
        ]);
    }

    function it_can_create_default_labels_given_a_singular_name()
    {
        $this->autoLabels('Thing');
        $this->labels()->shouldBe([
            'name' => 'Things',
            'singular_name' => 'Thing',
            'add_new_item' => 'Add New Thing',
            'edit_item' => 'Edit Thing',
            'new_item' => 'New Thing',
            'view_item' => 'View Thing',
            'search_items' => 'Search Things',
            'not_found' => 'No things found',
            'not_found_in_trash' => 'No things found in trash',
            'all_items' => 'All Things',
            'archives' => 'Thing Archives',
            'insert_into_item' => 'Insert into thing',
            'uploaded_to_this_item' => 'Uploaded to this thing',
        ]);
    }

    function it_handles_words_ending_with_a_y_differently()
    {
        $this->autoLabels('Category');
        $this->labels()->shouldBe([
            'name' => 'Categories',
            'singular_name' => 'Category',
            'add_new_item' => 'Add New Category',
            'edit_item' => 'Edit Category',
            'new_item' => 'New Category',
            'view_item' => 'View Category',
            'search_items' => 'Search Categories',
            'not_found' => 'No categories found',
            'not_found_in_trash' => 'No categories found in trash',
            'all_items' => 'All Categories',
            'archives' => 'Category Archives',
            'insert_into_item' => 'Insert into category',
            'uploaded_to_this_item' => 'Uploaded to this category',
        ]);
    }

    function it_can_choose_a_menu_icon()
    {
        $this->icon()->shouldBe('dashicons-admin-post');
        $this->icon(Dashicon::VIDEO_ALT_2);
        $this->icon()->shouldBe('dashicons-video-alt2');
    }
}
