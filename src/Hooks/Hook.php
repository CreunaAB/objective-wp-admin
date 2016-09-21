<?php

namespace Creuna\ObjectiveWpAdmin\Hooks;

interface Hook
{
    const VERY_HIGH_PRIORITY = 500;
    const HIGH_PRIORITY = 200;
    const MEDIUM_PRIORITY = 100;
    const LOW_PRIORITY = 10;

    /**
     * The id of the hook that this action wishes
     * to be attached to.
     *
     * @return string
     */
    public function hook();

    /**
     * The hook can provide a priority in the form of an integer.
     */
    /* public $priority = self::LOW_PRIORITY; */
}
