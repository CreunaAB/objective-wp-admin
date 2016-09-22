<?php

namespace Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Hooks\Hook;
use Exception;

class Admin
{
    protected $adapter;
    protected $hooks = [];

    public function __construct(AdminAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Creates a new instance, preemtively adding the hooks
     * to the system.
     *
     * @return Admin
     */
    public static function reset()
    {
        $admin = new static(new WordPressAdminAdapter);
        $admin->prepare();
        return $admin;
    }

    private function prepare()
    {
        // Add a listener to the first hook that we can access,
        // and add our own hooks at that point.
        $this->adapter->action('plugins_loaded', function () use ($admin) {
            $admin->execute();
        }, 1000);

        $this->hook(new Reset/ResetToolbarAction);
        $this->hook(new Reset/ResetDashboardAction);
        $this->hook(new Reset/ResetMenuAction);
    }

    /**
     * Hooks into the system using the provided adapter.
     *
     * If the instance was not created using ::reset(),
     * this method must be called manually to forward
     * the calls to the system. Basically for testing only.
     */
    public function execute()
    {
        foreach ($this->hooks as $hook) {
            $callback = function (...$args) use ($hook) {
                return $hook->call($this->adapter, $args);
            };

            if ($hook instanceof Filter) {
                $this->adapter->filter($hook->event(), $callback, 1000);
            } elseif ($hook instanceof Action) {
                $this->adapter->action($hook->event(), $callback, 1000);
            } else {
                throw new Exception(get_class($hook).' is neither an action nor a filter.');
            }
        }
    }

    public function hook(Hook $hook)
    {
        $this->hooks[] = $hook;
    }
}
