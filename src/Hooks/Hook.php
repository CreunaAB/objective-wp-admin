<?php

namespace Creuna\ObjectiveWpAdmin\Hooks;

use Creuna\ObjectiveWpAdmin\AdminAdapter;

interface Hook
{
    /**
     * The name of the event to hook into.
     *
     * @return string
     */
    public function event();

    /**
     * The callback, which will be called when
     * the event fires.
     *
     * @param AdminAdapter $adapter Access to the system.
     * @param array        $args    The arguments sent by the system to the hook.
     */
    public function call(AdminAdapter $admin, array $args);
}
