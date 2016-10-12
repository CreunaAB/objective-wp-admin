<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Field;
use Creuna\ObjectiveWpAdmin\Persistance\FieldBase;

class BooleanField implements Field
{
    use FieldBase;

    protected $defaultValue = false;

    public function view()
    {
        return new BooleanFieldView($this);
    }

    public function export($value)
    {
        return !! $value;
    }
}
