<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;

class PostTypeEditPageAction implements Action
{
    protected $type;

    public function __construct(PostType $type)
    {
        $this->type = $type;
    }

    public function event()
    {
        return 'edit_form_after_editor';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        list($post) = $args;
        $name = PostTypeUtils::postTypeName($this->type);

        if ($post->post_type !== $name) {
            return;
        }

        $schema = new Schema;
        $this->type->describe($schema);

        if (count($schema->fields()) === 0) {
            return;
        }

        $schema->registerAndEnqueueAssets($adapter);

        $widgets = array_map(function ($field) use ($adapter, $post) {
            $view = $field->view();
            $value = $adapter->getPostMeta($post->ID, $field->name(), true);
            if (is_array($value)) {
                return $view->render(array_map('htmlspecialchars', $value));
            }
            return $view->render(htmlspecialchars($value));
        }, $schema->fields());

        $rows = implode('', $widgets);

        echo "
            <table class='form-table'>
                <tbody>
                    $rows
                </tbody>
            </table>
        ";
    }
}
