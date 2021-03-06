<?php

namespace Creuna\ObjectiveWpAdmin\Persistence;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Event;

class PostTypeRegisterAction implements Action
{
    protected $type;

    public function __construct(PostType $type)
    {
        $this->type = $type;
    }

    public function event()
    {
        return Event::init();
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        $type = get_class($this->type);
        $segments = explode('\\', $type);
        $singular = end($segments);

        $schema = new Schema();
        $schema->autoLabels($singular);
        $this->type->describe($schema);

        $slug = PostTypeUtils::postTypeName($this->type);

        $adapter->registerPostType(
            $slug,
            [
                'labels' => $schema->labels(),
                'public' => true,
                'supports' => $schema->supports() ?: false,
                'rewrite' => [
                    'with_front' => false,
                    'slug' => $slug,
                    'feeds' => false,
                    'pages' => false,
                ],
                'menu_icon' => $schema->icon(),
            ]
        );
    }
}
