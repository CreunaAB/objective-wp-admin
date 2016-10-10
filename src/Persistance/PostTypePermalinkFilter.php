<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;
use DateTime;

class PostTypePermalinkFilter implements Filter
{
    protected $type;

    public function __construct(PostType $type)
    {
        $this->type = $type;
    }

    public function event()
    {
        return 'post_type_link';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        list($permalink, $post, $leavename) = $args;

        $postName = PostTypeUtils::postTypeName($this->type);

        if ($post->post_type !== $postName) {
            return $permalink;
        }

        $parsedPost = PostTypeUtils::parsePost($adapter, $this->type, $post);

        preg_match_all('/\/:([^\/]+)/', $permalink, $matches);

        list(, $params) = $matches;

        foreach ($params as $param) {
            if ($param === 'slug' && $leavename) {
                $permalink = str_replace(':slug', '%postname%', $permalink);
                continue;
            }
            try {
                $value = $parsedPost->{$param};

                if ($value instanceof DateTime) {
                    $value = $value->format('Y-m-d');
                } elseif (is_object($value)) {
                    $value = $value->__toString();
                }

                $value = urlencode($value);

                $permalink = str_replace(":$param", $value, $permalink);
            } catch (\Exception $e) {
            }
        }

        return $permalink;
    }
}
