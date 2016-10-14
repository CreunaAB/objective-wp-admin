<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Field;
use Creuna\ObjectiveWpAdmin\Persistance\FieldBase;
use Creuna\ObjectiveWpAdmin\Persistance\Fields\Editor;

class RichTextField implements Field
{
    use FieldBase;

    protected $defaultValue = '';
    protected $editor;

    public function view()
    {
        return new RichTextFieldView($this);
    }

    public function editor(Editor $editor = null)
    {
        if (!isset($editor)) {
            return $this->editor ?: new Editor();
        }
        $this->editor = $editor;
        return $this;
    }
}
