<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Field;
use Creuna\ObjectiveWpAdmin\Persistance\FieldBase;

class MediaField implements Field
{
    use FieldBase;

    protected $multiple = false;

    public function view()
    {
        return new MediaFieldView($this);
    }

    public function multiple()
    {
        $this->multiple = true;
        return $this;
    }

    public function holdsArray()
    {
        return $this->multiple;
    }

    public function export($value)
    {
        if ($this->holdsArray()) {
            return array_map([$this, 'getSrc'], $value);
        }
        if (is_array($value)) {
            $value = $value[0];
        }
        return $this->getSrc($value);
    }

    private function getSrc($id)
    {
        return function ($size) use ($id) {
            $result = wp_get_attachment_image_src($id, $size);
            if ($result) {
                return $result[0];
            }
            return null;
        };
    }
}
