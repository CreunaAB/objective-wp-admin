<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\FieldView;

class RichTextFieldView implements FieldView
{
    public function render($value)
    {
        return "
            <tr>
                <td scope='row' colspan='2'>
                    <textarea
                        type='text'
                        class='regular-text'
                        id='field_{$this->field->name()}'
                        name='{$this->field->name()}'
                    >$value</textarea>
                </td>
            </tr>
        ";
    }

    public function parseValue($value)
    {
        return $value;
    }
}
