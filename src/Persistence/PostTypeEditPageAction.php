<?php

namespace Creuna\ObjectiveWpAdmin\Persistence;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Event;
use Creuna\ObjectiveWpAdmin\Persistence\PostType;
use Creuna\ObjectiveWpAdmin\Persistence\Schema;
use Creuna\ObjectiveWpAdmin\Admin;

class PostTypeEditPageAction implements Action
{
    protected $type;
    protected $admin;

    public function __construct(PostType $type, Admin $admin)
    {
        $this->type = $type;
        $this->admin = $admin;
    }

    public function event()
    {
        return Event::editFormAfterEditor();
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

        $schema->registerAndEnqueueAssets($adapter, $this->admin);

        $widgets = array_map(function ($field) use ($adapter, $post) {
            $view = $field->view($this->admin);
            $value = $adapter->getPostMeta($post->ID, $field->name(), true);
            if ($value === '') {
                $value = $field->defaults();
            } else {
                $value = $field->deserialize($value);
            }
            return $view->render($this->escape($value));
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

    private function escape($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'escape'], $value);
        }

        if (is_string($value)) {
            return htmlspecialchars($value);
        }

        return $value;
    }
}
