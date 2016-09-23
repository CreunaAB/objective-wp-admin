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
     * Receives the value of the field from $_POST,
     * and should return the parsed value that should
     * be saved to the post. For example, a number input
     * should cast the string to an int.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($value);
}
