<?php

namespace Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Hooks\Hook;
use Creuna\ObjectiveWpAdmin\Persistence\PostTypeUtils;
use Creuna\ObjectiveWpAdmin\Persistence\Repository;
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
        }, 1, 0);

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

            $event = $hook->event();

            if ($hook instanceof Filter) {
                $this->adapter->filter(
                    $event->name,
                    $callback,
                    $priority,
                    $event->arity
                );
            } elseif ($hook instanceof Action) {
                $this->adapter->action(
                    $event->name,
                    $callback,
                    $priority,
                    $event->arity
                );
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
        $this->hook(new Persistence\PostTypeRegisterAction($postType));
        $this->hook(new Persistence\PostTypeEditPageAction($postType, $this));
        $this->hook(new Persistence\PostTypeSaveAction($postType));
        $this->hook(new Persistence\PostTypePermalinkAction($postType));
        $this->hook(new Persistence\PostTypePermalinkFilter($postType));
        $this->hook(new Persistence\PostTypeCustomizeEditorFilter($postType));
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

    /**
     * Gets the post type class name from an object, which can either be
     * a WP_Post or a dynamic object created from a repository before.
     *
     * @param WP_Post|DynamicObject $post
     *
     * @return string
     */
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
