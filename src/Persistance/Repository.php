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
        $limit = isset($this->constraints['limit']) ? $this->constraints['limit'] : -1; // -1 returns all posts
        $offset = isset($this->constraints['offset']) ? $this->constraints['offset'] : 0;
        $where = isset($this->constraints['where']) ? $this->constraints['where'] : [];
        $before = isset($this->constraints['before']) ? $this->constraints['before'] : null;
        $after = isset($this->constraints['after']) ? $this->constraints['after'] : null;

        $args = [
            'posts_per_page' => $limit,
            'offset' => $offset,
            'post_type' => PostTypeUtils::postTypeName($this->type),
        ];

        if (isset($before) || isset($after)) {
            $args['date_query'] = [];
        }
        if (isset($before)) {
            $args['date_query']['before'] = $before->format(DateTime::ISO8601);
        }
        if (isset($after)) {
            $args['date_query']['after'] = $after->format(DateTime::ISO8601);
        }

        if (count($where) > 0) {
            $args['meta_query'] = [ 'relation' => 'AND' ];
        }

        foreach ($where as $predicate) {
            extract($predicate);

            if ($field === 'slug') {
                if ($operator !== '=') {
                    throw new \Exception('The slug can only be filtered with the "=" operator');
                }
                $args['name'] = $value;
                continue;
            }

            $args['meta_query'][] = [
                'key' => $field,
                'value' => $value,
                'compare' => $operator,
            ];
        }

        return array_map([$this, 'wrap'], $this->adapter->getPosts($args));
    }

    public function wrap($post)
    {
        return PostTypeUtils::parsePost($this->adapter, $this->type, $post);
    }

    /**
     * Filters the query by field constraints.
     *
     * @param string $field    The name of the field to compare against.
     * @param string $operator The operator to use.
     * @param mixed  $value    The value that is required on the field.
     *
     * @return Repository
     */
    public function where($field, $operator, $value)
    {
        $constraints = $this->constraints;
        $constraints['where'] = isset($constraints['where']) ? $constraints['where'] : [];
        $constraints['where'][] = compact('field', 'operator', 'value');
        return new static($this->adapter, $this->type, $constraints);
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

    public function before(DateTime $date)
    {
        $constraints = $this->constraints;
        $constraints['before'] = $date;
        return new static($this->adapter, $this->type, $constraints);
    }

    public function after(DateTime $date)
    {
        $constraints = $this->constraints;
        $constraints['after'] = $date;
        return new static($this->adapter, $this->type, $constraints);
    }
}
