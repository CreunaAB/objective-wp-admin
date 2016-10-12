<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Field;
use Creuna\ObjectiveWpAdmin\Persistance\FieldBase;

class StringField implements Field
{
    use FieldBase;

    protected $defaultValue = '';

    public function view()
    {
        return new StringFieldView($this);
    }
}
