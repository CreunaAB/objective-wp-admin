<?php

namespace Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Actions\AddPostTypeAction;

class PostType
{
    protected $admin;
    protected $name;
    protected $fields;

    public function __construct(Admin $admin, $name, array $fields = [])
    {
        $this->admin = $admin;
        $this->name = $name;
        $this->fields = $fields;
    }

    public function register()
    {
        $this->admin->hook(new AddPostTypeAction($this->name, $this->fields));
    }
}
