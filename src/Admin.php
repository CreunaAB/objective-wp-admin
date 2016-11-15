<?php

namespace Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Hooks\Hook;
use Creuna\ObjectiveWpAdmin\Persistance\PostTypeUtils;
use Creuna\ObjectiveWpAdmin\Persistance\Repository;
use Exception;

class Admin
{
    protected $adapter;
    protected $hooks = [];
    protected $types = [];

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
        $this->adapter->action('init', function () {
            $this->execute();
        }, 1);

        $this->hook(new Reset\ResetToolbarAction);
        $this->hook(new Reset\ResetDashboardAction);
        $this->hook(new Reset\ResetMenuAction);
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

            $priority = isset($hook->priority)
                ? $hook->priority
                : 1000;

            if ($hook instanceof Filter) {
                $this->adapter->filter($hook->event(), $callback, $priority);
            } elseif ($hook instanceof Action) {
                $this->adapter->action($hook->event(), $callback, $priority);
            } else {
                throw new Exception(get_class($hook).' is neither an action nor a filter.');
            }
        }
    }

    public function hook(Hook $hook)
    {
        $this->hooks[] = $hook;
    }

    /**
     * Registers a post type.
     *
     * @param string $type The full name of the PostType class.
     */
    public function registerType($type)
    {
        $postType = new $type;
        $this->hook(new Persistance\PostTypeRegisterAction($postType));
        $this->hook(new Persistance\PostTypeEditPageAction($postType));
        $this->hook(new Persistance\PostTypeSaveAction($postType));
        $this->hook(new Persistance\PostTypePermalinkAction($postType));
        $this->hook(new Persistance\PostTypePermalinkFilter($postType));
        $this->hook(new Persistance\PostTypeCustomizeEditorFilter($postType));
        $this->types[] = $postType;
    }

    /**
     * Creates a Repository instance that can query the database
     * for posts of a specific post type.
     *
     * @param string $type The qualified name of the PostType class.
     *
     * @return Repository
     */
    public function repository($type)
    {
        return new Repository($this->adapter, new $type);
    }

    public function typeOf($post)
    {
        if ($post instanceof \WP_Post) {
            foreach ($this->types as $type) {
                if ($post->post_type === PostTypeUtils::postTypeName($type)) {
                    return get_class($type);
                }
            }
            throw new Exception("No registered type that match the slug '{$post->post_type}'");
        }
        return $post->_type;
    }
}
