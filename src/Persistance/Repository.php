<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use DateTime;

class Repository
{
    protected $adapter;
    protected $type;
    protected $constraints;

    public function __construct(AdminAdapter $adapter, PostType $type, array $constraints = [])
    {
        $this->adapter = $adapter;
        $this->type = $type;
        $this->constraints = $constraints;
    }

    /**
     * Get all published posts of this type.
     *
     * @return array
     */
    public function all()
    {
        return array_map([$this, 'wrap'], $this->adapter->getPosts([
            'posts_per_page' => $this->constraints['limit'] ?? -1, // -1 returns all posts
            'offset' => $this->constraints['offset'] ?? 0,
            'post_type' => PostTypeUtils::postTypeName($this->type),
        ]));
    }

    private function wrap($post)
    {
        return PostTypeUtils::parsePost($this->adapter, $this->type, $post);
    }

    /**
     * Limits the query to a certain number of results.
     *
     * @param int $count
     *
     * @return Repository
     */
    public function take($count)
    {
        $constraints = $this->constraints;
        $constraints['limit'] = $count;
        return new static($this->adapter, $this->type, $constraints);
    }

    /**
     * Offsets the query to skip the first few results.
     *
     * @param int $offset
     *
     * @return Repository
     */
    public function skip($offset)
    {
        $constraints = $this->constraints;
        $constraints['offset'] = $offset;
        return new static($this->adapter, $this->type, $constraints);
    }
}
