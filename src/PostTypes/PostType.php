<?php

namespace Creuna\ObjectiveWpAdmin\PostTypes;

class PostType
{
    public $name;
    public $options;

    public function __construct($name, array $options = [])
    {
        $this->name = $name;
        $this->options = $options;
    }
}
