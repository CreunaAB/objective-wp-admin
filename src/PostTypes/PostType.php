<?php

namespace Creuna\ObjectiveWpAdmin\PostTypes;

class PostType
{
    protected $name;
    protected $options;

    public function __construct($name, array $options = [])
    {
        $this->name = $name;
        $this->options = $options;
    }
}
