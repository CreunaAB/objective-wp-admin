<?php

namespace Creuna\ObjectiveWpAdmin\Hooks;

use Creuna\ObjectiveWpAdmin\AdminAdapter;

interface Hook
{
    /**
     * The event to hook into.
     *
     * @return Event
     */
    public function event();

    /**
     * The callback, which will be called when
     * the event fires.
     *
     * @param AdminAdapter $admin   Access to the system.
     * @param array        $args    The arguments sent by the system to the hook.
     */
    public function call(AdminAdapter $admin, array $args);
}
