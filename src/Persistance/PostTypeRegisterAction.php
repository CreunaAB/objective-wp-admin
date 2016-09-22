<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;

class PostTypeRegisterAction implements Action
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function event()
    {
        return 'init';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        $type = get_class($this->type);
        $segments = explode('\\', $type);
        $singular = end($segments);

        $adapter->registerPostType(
            strtolower($type),
            [
                'labels' => [
                    'name' => "{$singular}s",
                    'singular_name' => $singular,
                ],
                'public' => true,
                'supports' => [
                    'title' => false,
                    'editor' => false,
                    'author' => false,
                    'thumbnail' => false,
                    'excerpt' => false,
                    'trackbacks' => false,
                    'custom-fields' => false,
                    'comments' => false,
                    'revisions' => false,
                    'page-attributes' => false,
                    'post-formats' => false,
                ],
            ]
        );
    }
}
