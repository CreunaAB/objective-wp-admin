<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistance\FieldView;

class SelectFieldView implements FieldView
{
    protected $field;

    public function __construct(SelectField $field)
    {
        $this->field = $field;
    }

    public function render($value)
    {
        $multiple = $this->field->holdsArray() ? 'multiple' : '';
        $name = "custom_{$this->field->name()}";
        if ($this->field->holdsArray()) {
            $name .= '[]';
        }

        return "
            <tr>
                <th scope='row'>
                    <label for='field_{$this->field->name()}'>
                        {$this->field->title()}
                    </label>
                </th>
                <td>
                    <select
                        $multiple
                        style='width: 100%'
                        id='field_{$this->field->name()}'
                        name='$name'
                    >
                        {$this->options($value)}
                    </select>
                </td>
            </tr>
        ";
    }

    protected function options($active)
    {
        if (!is_array($active)) {
            $active = [$active];
        }
        $options = $this->field->options();
        $options = array_map(function ($name) use ($options, $active) {
            $value = $options[$name];
            $selected = in_array($name, $active) ? 'selected="selected"' : '';

            return "
                <option $selected value='$name'>$value</option>
            ";
        }, array_keys($options));

        return implode($options, '');
    }

    public function parseValue($value)
    {
        return $value;
    }

    public function assets(AdminAdapter $adapter)
    {
    }
}
