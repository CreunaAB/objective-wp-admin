<?php

namespace Creuna\ObjectiveWpAdmin;

class Slugify
{
    public static function slug($string)
    {
        $slug = strtolower(implode('_', explode('\\', $string)));
        $length = strlen($slug);
        if ($length > 20) {
            return substr($slug, $length - 20);
        }
        return $slug;
    }
}
