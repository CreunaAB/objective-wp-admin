<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

class Schema
{
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

    public function body()
    {
        $this->support('editor');
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
}
