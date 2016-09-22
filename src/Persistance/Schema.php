<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

class Schema
{
    protected $fields = [];

    public function fields()
    {
        return $this->fields;
    }

    private function add(Field $field)
    {
        $this->fields[] = $field;
        return $field;
    }

    public function string($name, $title = null)
    {
        return $this->add(new Fields\StringField($name, $title));
    }
}
