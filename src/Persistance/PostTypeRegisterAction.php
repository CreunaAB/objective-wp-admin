<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;

class PostTypeRegisterAction implements Action
{
    protected $type;

    public function __construct(PostType $type)
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

        $schema = new Schema();
        $this->type->describe($schema);

        $slug = strtolower(implode('_', $segments));

        $adapter->registerPostType(
            $slug,
            [
                'labels' => [
                    'name' => "{$singular}s",
                    'singular_name' => $singular,
                ],
                'public' => true,
                'supports' => $schema->supports() ?: false,
                'rewrite' => [
                    'with_front' => false,
                    'slug' => $slug,
                    'feeds' => false,
                    'pages' => false,
                ],
            ]
        );
    }
}
