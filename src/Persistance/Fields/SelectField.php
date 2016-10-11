<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Field;
use Creuna\ObjectiveWpAdmin\Persistance\FieldBase;

class SelectField implements Field
{
    use FieldBase;

    protected $options = [];
    protected $multiple = false;

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
}
