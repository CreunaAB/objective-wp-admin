<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;

class PostTypeSaveAction implements Action
{
    protected $type;

    public function __construct(PostType $type)
    {
        $this->type = $type;
    }

    public function event()
    {
        return 'save_post';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        $post = $adapter->getPost($args[0]);
        $name = strtolower(implode('_', explode('\\', get_class($this->type))));

        if ($post->post_type != $name) {
            return;
        }

        $schema = new Schema;
        $this->type->describe($schema);
        foreach ($schema->fields() as $field) {
            $fieldName = $field->name();
            $newValue = $_POST[$fieldName];

            if (!isset($newValue) && $field->isRequired()) {
                throw new Exception("The $fieldName field is required");
            }

            $adapter->setPostMeta($post->ID, $fieldName, $newValue);
        }
    }
}
