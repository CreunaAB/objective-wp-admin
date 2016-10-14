<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use Creuna\ObjectiveWpAdmin\Persistance\PostTypeUtils;

class PostTypeCustomizeEditorFilter implements Filter
{
    protected $type;

    public function __construct(PostType $type)
    {
        $this->type = $type;
    }

    public function event()
    {
        return 'wp_editor_settings';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        global $post;
        list($settings, $editorId) = $args;

        $type = PostTypeUtils::postTypeName($this->type);

        if (!isset($post) ||
            $post->post_type !== $type ||
            $editorId !== 'content'
        ) {
            return $settings;
        }

        $schema = new Schema;
        $this->type->describe($schema);

        if (!$schema->supports('editor') || $schema->bodyEditor() === null) {
            return $settings;
        }

        return $schema->bodyEditor()->config();
    }
}
