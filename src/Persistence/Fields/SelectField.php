<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;
use Creuna\ObjectiveWpAdmin\Admin;

class SelectField implements Field
{
    use FieldBase;

    protected $options = [];
    protected $multiple = false;
    protected $defaultValue = null;
    protected $relatedPostType = null;
    protected $relatedPostTypeTitleField = 'title';

    public function view(Admin $admin)
    {
        return new SelectFieldView($this, $admin);
    }

    public function option($name, $value = null)
    {
        $value = $value ?: $name;

        $this->options[$name] = $value;

        return $this;
    }

    public function from($postType, $titleField = 'title')
    {
        $this->relatedPostType = $postType;
        $this->relatedPostTypeTitleField = $titleField;

        return $this;
    }

    public function options(Admin $admin)
    {
        if (isset($this->relatedPostType)) {
            $related = $admin->repository($this->relatedPostType)->all();

            foreach ($related as $object) {
                $id = isset($object->slug) ? $object->slug : $object->id;
                $this->options[$id] = $object->{$this->relatedPostTypeTitleField};
            }
        }
        return $this->options;
    }

    public function multiple()
    {
        $this->multiple = true;
        return $this;
    }

    public function holdsArray()
    {
        return $this->multiple;
    }

    public function export($value)
    {
        if ($this->multiple && $value == null) {
            return [];
        }
        return $value;
    }
}
