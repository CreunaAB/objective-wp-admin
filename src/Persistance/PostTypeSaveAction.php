<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Exception;

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
        $name = PostTypeUtils::postTypeName($this->type);

        if ($post->post_type != $name) {
            return;
        }

        $schema = new Schema;
        $this->type->describe($schema);
        foreach ($schema->fields() as $field) {
            $fieldName = $field->name();

            if ((!isset($_POST[$fieldName]) || $_POST[$fieldName] === '') && $field->isRequired()) {
                throw new Exception("The $fieldName field is required");
            }

            if (!isset($_POST[$fieldName])) {
                continue;
            }

            $newValue = $field->view()->parseValue($_POST[$fieldName]);

            $adapter->setPostMeta($post->ID, $fieldName, $newValue);
        }
    }
}
