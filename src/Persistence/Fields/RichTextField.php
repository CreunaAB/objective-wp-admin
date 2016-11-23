<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;
use Creuna\ObjectiveWpAdmin\Persistence\Fields\Editor;

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
            return $this->editor ?: Editor::make();
        }
        $this->editor = $editor;
        return $this;
    }
}
