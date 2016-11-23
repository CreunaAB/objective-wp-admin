<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;

class SelectField implements Field
{
    use FieldBase;

    protected $options = [];
    protected $multiple = false;
    protected $defaultValue = null;

    public function view()
    {
        return new SelectFieldView($this);
    }

    public function option($name, $value = null)
    {
        $value = $value ?: $name;

        $this->options[$name] = $value;

        return $this;
    }

    public function options()
    {
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
