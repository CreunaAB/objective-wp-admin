<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\FieldView;

class StringFieldView implements FieldView
{
    protected $field;

    public function __construct(StringField $field)
    {
        $this->field = $field;
    }

    public function render($value)
    {
        $value = htmlspecialchars($value);
        return "
            <input name='{$this->field->name()}' value='$value'>
        ";
    }

    public function parseValue($value)
    {
        return $value;
    }
}
