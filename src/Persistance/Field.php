<?php

namespace Creuna\ObjectiveWpAdmin\Persistance;

use Creuna\ObjectiveWpAdmin\AdminAdapter;

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
     * Sets or gets the default value of this field.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function defaults($value = null);

    /**
     * Tells the PostType whether or not this field
     * is required to be present on the post.
     *
     * @return bool
     */
    public function isRequired();

    /**
     * Tells the PostType that the field holds multiple values.
     *
     * @return bool
     */
    public function holdsArray();

    /**
     * Transform the value before export.
     *
     * @param  mixed $value The raw field value.
     * @return mixed
     */
    public function export($value);
}
