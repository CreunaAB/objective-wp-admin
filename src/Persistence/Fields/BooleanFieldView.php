<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistence\FieldView;

class BooleanFieldView implements FieldView
{
    protected $field;

    public function __construct(BooleanField $field)
    {
        $this->field = $field;
    }

    public function render($value)
    {
        $checked = $value ? 'checked' : '';
        return "
            <tr>
                <th scope='row'>
                    <label for='field_{$this->field->name()}'>
                        {$this->field->title()}
                    </label>
                </th>
                <td>
                    <input
                        type='hidden'
                        name='custom_{$this->field->name()}'
                        value='$value'
                    >
                    <input
                        type='checkbox'
                        id='field_{$this->field->name()}'
                        name='custom_{$this->field->name()}'
                        $checked
                    >
                </td>
            </tr>
        ";
    }

    public function parseValue($value)
    {
        return $value === 'on';
    }

    public function assets(AdminAdapter $adapter)
    {
    }
}
