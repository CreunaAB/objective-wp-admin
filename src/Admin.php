<?php

namespace Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Hooks\Hook;
use Creuna\ObjectiveWpAdmin\PostTypes\PostTypeBuilder;
use Creuna\ObjectiveWpAdmin\PostTypes\RegisterPostTypeAction;
use Creuna\ObjectiveWpAdmin\WordPressAdminAdapter;

class Admin
{
    protected $adapter;

    public function __construct(AdminAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public static function make()
    {
        return new static(new WordPressAdminAdapter);
    }

    /**
     * Registers a post type.
     *
     *     $admin->type('things', function (PostTypeBuilder $builder) {
     *         return $builder
     *             ->labels([ 'featured_image' => 'Featured Image of the Thing' ]);
     *     });
     *
     * @param string   $name  The name of the post type (think database table).
     * @param callable $build Receives a PostTypeBuilder which builds the PostType.
     */
    public function type($name, callable $build)
    {
        $builder = $build(PostTypeBuilder::make($name));
        $postType = $builder->get();
        $this->hook(new RegisterPostTypeAction($postType));
    }

    /**
     * Registers a hook.
     *
     * @param Hook $hook
     */
    public function hook(Hook $hook)
    {
        $priority = $this->priority($hook);

        if ($hook instanceof Action) {
            return $this->adapter->action(
                $hook->hook(),
                $this->callback($hook, 'fire'),
                $priority
            );
        }

        if ($hook instanceof Filter) {
            return $this->adapter->filter(
                $hook->hook(),
                $this->callback($hook, 'filter'),
                $priority
            );
        }

        throw new \Exception(get_class($hook).' cannot be used as a Hook.');
    }

    private function callback(Hook $hook, $method)
    {
        if (!method_exists($hook, $method)) {
            throw new \Exception(get_class($hook)." does not provide the required $method method.");
        }

        $callable = [$hook, $method];

        if ($hook instanceof Filter) {
            return $callable;
        }

        return function (...$args) use ($callable) {
            return $callable($this->adapter, ...$args);
        };
    }

    private function priority(Hook $hook)
    {
        if (property_exists($hook, 'priority')) {
            return $hook->priority;
        }
        return Hook::LOW_PRIORITY;
    }
}
