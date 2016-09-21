<?php

namespace spec\Creuna\ObjectiveWpAdmin;

use Creuna\ObjectiveWpAdmin\Admin;
use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Hooks\Filter;
use Creuna\ObjectiveWpAdmin\Hooks\Hook;
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

    function it_enables_actions(AdminAdapter $adapter, TestAction $action)
    {
        $action->hook()->willReturn('test_action');
        $action->fire(Argument::type(AdminAdapter::class))->willReturn(true);

        $this->hook($action);

        $adapter->action(
            'test_action',
            $this->argumentIsCallbackGetsCalledWith(),
            10
        )->shouldHaveBeenCalled();
    }

    function it_enables_filters(AdminAdapter $adapter, TestFilter $filter)
    {
        $filter->hook()->willReturn('test_filter');
        $filter->filter()->willReturn(true);

        $this->hook($filter);

        $adapter->filter(
            'test_filter',
            $this->argumentIsCallbackGetsCalledWith(),
            10
        )->shouldHaveBeenCalled();
    }

    function it_sends_forth_the_priority_of_a_hook(AdminAdapter $adapter, TestAction $action)
    {
        $action->priority = 100;
        $action->hook()->willReturn('test_action');
        $this->hook($action);
        $adapter->action('test_action', Argument::type('callable'), 100)->shouldHaveBeenCalled();
    }

    private function argumentIsCallbackGetsCalledWith(...$args)
    {
        return Argument::that(function (callable $callback) use ($args) {
            return $callback(...$args);
        });
    }
}

class TestAction implements Action
{
    public function hook() {}
    public function fire() {}
}

class TestFilter implements Filter
{
    public function hook() {}
    public function filter() {}
}
