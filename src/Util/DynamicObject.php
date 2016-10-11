<?php

namespace Creuna\ObjectiveWpAdmin\Util;

use Closure;

class DynamicObject
{
    protected $fields = [];
    protected $methods = [];

    public function __construct(array $fields = [])
    {
        foreach ($fields as $name => $field) {
            if ($field instanceof Closure) {
                $this->methods[$name] = $field;
            } else {
                $this->fields[$name] = $field;
            }
        }
    }

    public function __get($field)
    {
        return $this->fields[$field];
    }

    public function __call($method, $args)
    {
        $callback = $this->methods[$method];
        return $callback(...$args);
    }
}
