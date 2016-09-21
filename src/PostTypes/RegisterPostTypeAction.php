<?php

namespace Creuna\ObjectiveWpAdmin\PostTypes;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;

class RegisterPostTypeAction implements Action
{
    protected $postType;

    public function __construct(PostType $postType)
    {
        $this->postType = $postType;
    }

    public function hook()
    {
        return 'init';
    }

    public function fire(AdminAdapter $admin)
    {
        $admin->registerPostType($this->postType->name, $this->postType->options);
    }
}
