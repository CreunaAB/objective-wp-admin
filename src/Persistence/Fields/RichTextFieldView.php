<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistence\FieldView;
use Creuna\ObjectiveWpAdmin\Persistence\Fields\Editor;

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
                        <label for='custom_{$this->field->name()}'>
                            {$this->field->title()}
                        </label>
                        {$this->editor($value)}
                    </div>
                </th>
            </tr>
        ";
    }

    private function editor($value)
    {
        ob_start();
        $id = "custom_{$this->field->name()}";
        $value = htmlspecialchars_decode($value);
        wp_editor($value, $id, $this->field->editor()->config());
        return ob_get_clean();
    }

    public function parseValue($value)
    {
        return $value;
    }

    public function assets(AdminAdapter $adapter)
    {
    }
}
