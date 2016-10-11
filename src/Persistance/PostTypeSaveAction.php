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
            $fieldFormName = "custom_$fieldName";

            if ((!isset($_POST[$fieldFormName]) || $_POST[$fieldFormName] === '') && $field->isRequired()) {
                throw new Exception("The $fieldName field is required");
            }

            if (!isset($_POST[$fieldFormName])) {
                continue;
            }

            $newValue = $field->view()->parseValue($_POST[$fieldFormName]);

            $adapter->setPostMeta($post->ID, $fieldName, $newValue);
        }
    }
}
