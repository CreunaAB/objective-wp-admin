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

    /**
     * Gets the value after the form has been posted.
     *
     * @return mixed
     */
    public function value();
}
