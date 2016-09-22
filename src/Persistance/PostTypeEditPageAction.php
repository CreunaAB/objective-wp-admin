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
        $schema = new Schema;
        $this->type->describe($schema);
        $widgets = array_map(function ($field) use ($adapter, $post) {
            $view = $field->view();
            return $view->render($adapter->getPostMeta($post->ID, $field->name(), true));
        }, $schema->fields());
        echo implode('', $widgets);
    }
}
