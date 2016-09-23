<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

class PostTypeUtils
{
    public static function postTypeName(PostType $type)
    {
        return strtolower(implode('_', explode('\\', get_class($type))));
    }
}
