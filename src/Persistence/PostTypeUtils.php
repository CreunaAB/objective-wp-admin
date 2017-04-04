<?php

namespace Creuna\ObjectiveWpAdmin\Persistence;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistence\Fields\RichTextField;
use Creuna\ObjectiveWpAdmin\Persistence\Schema;
use Creuna\ObjectiveWpAdmin\Slugify;
use Creuna\ObjectiveWpAdmin\Util\DynamicObject;
use DateTime;
use WP_Post;

class PostTypeUtils
{
    public static function postTypeName(PostType $type)
    {
        return Slugify::slug(get_class($type));
    }

    public static function parsePost(AdminAdapter $adapter, PostType $type, $post)
    {
        $fields = [
            'id' => $post->ID,
            'slug' => $post->post_name,
            'createdAt' => new DateTime($post->post_date_gmt),
            'updatedAt' => new DateTime($post->post_modified_gmt),
            'status' => $post->post_status,
            '_type' => get_class($type),
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
            $value = $adapter->getPostMeta($post->ID, $name, true);
            if ($value === '') {
                $value = $field->defaults();
            } elseif ($field instanceof RichTextField) {
                $value = $adapter->applyFilters('the_content', $value);
            }
            $fields[$name] = $field->deserialize($value);
        }

        return new DynamicObject($fields);
    }
}
