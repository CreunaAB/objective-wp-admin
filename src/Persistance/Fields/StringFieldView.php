<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
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
        return "
            <tr>
                <th scope='row'>
                    <label for='field_{$this->field->name()}'>
                        {$this->field->title()}
                    </label>
                </th>
                <td>
                    <input
                        type='text'
                        class='regular-text'
                        id='field_{$this->field->name()}'
                        name='custom_{$this->field->name()}'
                        value='$value'
                    >
                </td>
            </tr>
        ";
    }

    public function parseValue($value)
    {
        return $value;
    }

    public function assets(AdminAdapter $adapter)
    {
    }
}
