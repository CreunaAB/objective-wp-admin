<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

interface Field
{
    /**
     * Instantiates the FieldView that will handle
     * the Field's UI.
     *
     * @return FieldView
     */
    public function view();

    /**
     * Returns the name of the field.
     *
     * @return string
     */
    public function name();

    /**
     * Tells the PostType whether or not this field
     * is required to be present on the post.
     *
     * @return bool
     */
    public function isRequired();
}