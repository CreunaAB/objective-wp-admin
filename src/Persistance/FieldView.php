<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

interface FieldView
{
    /**
     * Renders the field in HTML.
     *
     * @return string
     */
    public function render($value);
}
