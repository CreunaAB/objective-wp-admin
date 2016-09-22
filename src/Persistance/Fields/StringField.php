<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Field;

class StringField implements Field
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

    public function view()
    {
        return new StringFieldView($this);
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function name()
    {
        return $this->name;
    }
}
