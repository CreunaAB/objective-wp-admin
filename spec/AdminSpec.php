<?php

namespace spec\Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Admin;
use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminSpec extends ObjectBehavior
{
    function let(AdminAdapter $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Admin::class);
    }

    function it_hooks_into_the_underlying_system(AdminAdapter $adapter)
    {
        $this->hook(new TestAction);

        $this->execute();

        $adapter->action(
            'init', Argument::that(function ($callback) {
                return $callback() == 'result of calling TestAction::call';
            }), 1000
        )->shouldHaveBeenCalled();
    }

    function it_works_with_filters_too(AdminAdapter $adapter)
    {
        $this->hook(new TestFilter);

        $this->execute();

        $adapter->filter(
            'filter_name', Argument::that(function ($callback) {
                return $callback() == 'result of calling TestFilter::call';
            }), 1000
        )->shouldHaveBeenCalled();
    }
}

class TestAction implements Action
{
    public function event()
    {
        return 'init';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        return 'result of calling TestAction::call';
    }
}

class TestFilter implements Filter
{
    public function event()
    {
        return 'filter_name';
    }

    public function call(AdminAdapter $adapter, array $args)
    {
        return 'result of calling TestFilter::call';
    }
}
