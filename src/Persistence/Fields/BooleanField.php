<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;

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
