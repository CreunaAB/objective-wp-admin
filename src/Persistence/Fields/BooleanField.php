<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;
use Creuna\ObjectiveWpAdmin\Admin;

class BooleanField implements Field
{
    use FieldBase;

    protected $defaultValue = false;

    public function view(Admin $admin)
    {
        return new BooleanFieldView($this);
    }

    public function export($value)
    {
        return !! $value;
    }
}
