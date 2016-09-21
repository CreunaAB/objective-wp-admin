<?php

namespace Creuna\ObjectiveWpAdmin\Hooks;

interface Action extends Hook
{
    /**
     * An implementer of Action must supply a ::fire method
     * which will receive the AdminAdapter instance and the dynamic
     * arguments of the action hook.
     */
    /* public function fire(AdminAdapter $admin, ...$args); */
}
