<?php

namespace Creuna\ObjectiveWpAdmin\Persistence;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistence\Fields\RichTextField;
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

    public static function parsePost(AdminAdapter $adapter, PostType $type, $postToParse)
    {
        global $post;
        $postBefore = $post;

        $fields = [
            'id' => $postToParse->ID,
            'slug' => $postToParse->post_name,
            'createdAt' => new DateTime($postToParse->post_date_gmt),
            'updatedAt' => new DateTime($postToParse->post_modified_gmt),
            'status' => $postToParse->post_status,
            '_type' => get_class($type),
        ];

        $schema = new Schema;
        $type->describe($schema);

        if ($schema->supports('title')) {
            $fields['title'] = $postToParse->post_title;
        }

        if ($schema->supports('editor')) {
            $fields['body'] = self::applyRichTextFilters($adapter, $postToParse, $postToParse->post_content);
        }

        foreach ($schema->fields() as $field) {
            $name = $field->name();
            $value = $adapter->getPostMeta($postToParse->ID, $name, true);
            if ($value === '') {
                $value = $field->defaults();
            } elseif ($field instanceof RichTextField) {
                $value = self::applyRichTextFilters($adapter, $postToParse, $value);
            }
            $fields[$name] = $field->deserialize($value);
        }

        $post = $postBefore;

        return new DynamicObject($fields);
    }

    private static function applyRichTextFilters(AdminAdapter $adapter, WP_Post $p, $content)
    {
        // Apparently, to make embeds properly expand in the_content filter,
        // the global post object must be set.
        global $post;
        $post = $p;

        return $adapter->applyFilters('the_content', $content);
    }
}
