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
            <tr>
                <th scope='row'>
                    <label for='field_{$this->field->name()}'>
                        {$this->field->title()}
                    </label>
                </th>
                <td>
                    <input class='regular-text' id='field_{$this->field->name()}' name='{$this->field->name()}' value='$value'>
                </td>
            </tr>
        ";
    }

    public function parseValue($value)
    {
        return $value;
    }
}
