<?php

namespace Creuna\ObjectiveWpAdmin\Actions;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;

class AddPostTypeAction implements Action
{
    protected $name;
    protected $fields;

    public function __construct($name, array $fields)
    {
        $this->name = $name;
        $this->fields = $fields;
    }

    public function hook()
    {
        return 'init';
    }

    public function fire(AdminAdapter $admin)
    {
        $admin->registerPostType($this->name, []);
    }
}
