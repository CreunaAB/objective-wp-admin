<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;

class StringField implements Field
{
    use FieldBase;

    protected $defaultValue = '';

    public function view()
    {
        return new StringFieldView($this);
    }
}
