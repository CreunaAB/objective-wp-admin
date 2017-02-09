<?php

namespace Creuna\ObjectiveWpAdmin\Persistence;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistence\Fields\Editor;

class Schema
{
    protected $editor;
    protected $fields = [];
    protected $supports = [
        // 'title',
        // 'editor',
        // 'author',
        // 'thumbnail',
        // 'excerpt',
        // 'trackbacks',
        // 'custom-fields',
        // 'comments',
        // 'revisions',
        // 'page-attributes',
        // 'post-formats',
    ];
    protected $permastruct = '/:id';
    protected $labels = [
        // 'name'
        // 'singular_name'
        // 'add_new'
        // 'add_new_item'
        // 'edit_item'
        // 'new_item'
        // 'view_item'
        // 'search_items'
        // 'not_found'
        // 'not_found_in_trash'
        // 'parent_item_colon'
        // 'all_items'
        // 'archives'
        // 'insert_into_item'
        // 'uploaded_to_this_item'
        // 'featured_image'
        // 'set_featured_image'
        // 'remove_featured_image'
        // 'use_featured_image'
        // 'menu_name'
        // 'filter_items_list'
        // 'items_list_navigation'
        // 'items_list'
        // 'name_admin_bar'
    ];
    protected $icon = 'dashicons-admin-post';

    public function labels(array $labels = null)
    {
        if (isset($labels)) {
            $this->labels = array_merge($this->labels, $labels);
            return $this;
        }
        return $this->labels;
    }

    public function label($id, $value)
    {
        $this->labels[$id] = $value;
        return $this;
    }

    public function fields()
    {
        return $this->fields;
    }

    private function add(Field $field)
    {
        $this->fields[] = $field;
        return $field;
    }

    public function string($name, $title = null)
    {
        return $this->add(new Fields\StringField($name, $title));
    }

    public function richText($name, $title = null)
    {
        return $this->add(new Fields\RichTextField($name, $title));
    }

    public function select($name, $title = null)
    {
        return $this->add(new Fields\SelectField($name, $title));
    }

    public function media($name, $title = null)
    {
        return $this->add(new Fields\MediaField($name, $title));
    }

    public function boolean($name, $title = null)
    {
        return $this->add(new Fields\BooleanField($name, $title));
    }

    public function supports($key = null)
    {
        if (isset($key)) {
            return in_array($key, $this->supports);
        }
        return $this->supports;
    }

    public function support($function)
    {
        $this->supports[] = $function;
    }

    public function title()
    {
        $this->support('title');
    }

    public function body(Editor $editor = null)
    {
        $this->editor = $editor ?: Editor::make();
        $this->support('editor');
    }

    public function bodyEditor()
    {
        return $this->editor;
    }

    public function permastruct($value = null)
    {
        if (isset($value)) {
            $value = '/' . trim($value, '/');
            $this->permastruct = $value;
            return $this;
        }
        return $this->permastruct;
    }

    public function autoLabels($title)
    {
        $ucSingular = $title;
        $ucPlural = "{$title}s";
        $ucPlural = preg_replace('/ys$/', 'ies', $ucPlural);
        $lcSingular = strtolower($ucSingular);
        $lcPlural = strtolower($ucPlural);

        return $this->labels([
            'name' => "$ucPlural",
            'singular_name' => "$ucSingular",
            'add_new_item' => "Add New $ucSingular",
            'edit_item' => "Edit $ucSingular",
            'new_item' => "New $ucSingular",
            'view_item' => "View $ucSingular",
            'search_items' => "Search $ucPlural",
            'not_found' => "No $lcPlural found",
            'not_found_in_trash' => "No $lcPlural found in trash",
            'all_items' => "All $ucPlural",
            'archives' => "$ucSingular Archives",
            'insert_into_item' => "Insert into $lcSingular",
            'uploaded_to_this_item' => "Uploaded to this $lcSingular",
        ]);
    }

    public function registerAndEnqueueAssets(AdminAdapter $adapter, Admin $admin)
    {
        foreach ($this->fields() as $field) {
            $field->view($admin)->assets($adapter);
        }
    }

    public function icon($value = null)
    {
        if (isset($value)) {
            $this->icon = $value;
            return $this;
        }
        return $this->icon;
    }
}
