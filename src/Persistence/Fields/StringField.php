<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;
use Creuna\ObjectiveWpAdmin\Admin;

class StringField implements Field
{
    use FieldBase;

    protected $defaultValue = '';

    public function view(Admin $admin)
    {
        return new StringFieldView($this);
    }
}
