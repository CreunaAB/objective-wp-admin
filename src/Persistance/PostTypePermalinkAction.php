<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;

class PostTypePermalinkAction implements Action
{
    protected $type;

    public function __construct(PostType $type)
    {
        $this->type = $type;
    }

    public function event()
    {
        return 'wp_loaded';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        $slug = PostTypeUtils::postTypeName($this->type);

        // Create a schema
        $schema = new Schema;

        // Set the default permastruct
        $schema->permastruct("/$slug/:id");

        // Allow the type to override
        $this->type->describe($schema);

        // Set it
        $adapter->addPermastruct($slug, $schema->permastruct(), [
            'with_front' => false,
            'feed' => false,
            'paged' => false,
        ]);
    }
}
