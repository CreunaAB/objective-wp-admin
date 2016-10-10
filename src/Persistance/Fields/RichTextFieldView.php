<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\FieldView;

class RichTextFieldView implements FieldView
{
    protected $field;

    public function __construct(RichTextField $field)
    {
        $this->field = $field;
    }

    public function render($value)
    {
        return "
            <tr>
                <th scope='row' colspan='2'>
                    <div>
                        <label for='field_{$this->field->name()}'>
                            {$this->field->title()}
                        </label>
                    </div>
                    <textarea
                        style='width: 100%'
                        class='regular-text'
                        id='field_{$this->field->name()}'
                        name='{$this->field->name()}'
                    >$value</textarea>
                </th>
            </tr>
        ";
    }

    public function parseValue($value)
    {
        return $value;
    }
}
