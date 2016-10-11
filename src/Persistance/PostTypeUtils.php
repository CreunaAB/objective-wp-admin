<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use WP_Post;
use DateTime;

class PostTypeUtils
{
    public static function postTypeName(PostType $type)
    {
        return strtolower(implode('_', explode('\\', get_class($type))));
    }

    public static function parsePost(AdminAdapter $adapter, PostType $type, $post)
    {
        $fields = [
            'id' => $post->ID,
            'slug' => $post->post_name,
            'createdAt' => new DateTime($post->post_date_gmt),
            'updatedAt' => new DateTime($post->post_modified_gmt),
            'status' => $post->post_status,
        ];

        $schema = new Schema;
        $type->describe($schema);

        if ($schema->supports('title')) {
            $fields['title'] = $post->post_title;
        }

        if ($schema->supports('editor')) {
            $fields['body'] = $adapter->applyFilters('the_content', $post->post_content);
        }

        foreach ($schema->fields() as $field) {
            $name = $field->name();
            $fields[$name] = $adapter->getPostMeta($post->ID, $name, true);
        }

        return (object) $fields;
    }
}
