<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;

trait FieldBase
{
    protected $name;
    protected $title;
    protected $required;

    public function __construct($name, $title = null, $required = false)
    {
        $this->name = $name;
        $this->title = $title ?? ucwords($name);
        $this->required = $required;
    }

    public function required()
    {
        $this->required = true;
        return $this;
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function name()
    {
        return $this->name;
    }

    public function defaults($value = null)
    {
        if (isset($value)) {
            $this->defaultValue = $value;
            return $this;
        }
        return $this->defaultValue;
    }

    public function title()
    {
        return $this->title;
    }

    public function holdsArray()
    {
        return false;
    }

    public function export($value)
    {
        return $value;
    }
}
