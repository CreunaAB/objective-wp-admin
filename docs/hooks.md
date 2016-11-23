[← Back to TOC](index.md)

# Hooks
WordPress uses a very basic "pub/sub" hook system, which enforces some structure when the
entire world is globally mutable and everything must come in a specific order.

There is no way out of this constraint. It's thoroughly embedded in the WordPress
architecture. The best thing we can do is to treat the entire world as an object that we
can modify. We do that via an interface called the `AdminAdapter`, which can be mocked in
tests so that we can validate and isolate our interaction with the world.

Those interactions must also be hooked up to the pub/sub system somehow. Here's what we've
done to make it cleaner; A hook is simply a class that implements the `Hook` interface,
which forces us to implement two methods.

```php
interface Hook
{
    function event(): Creuna\ObjectiveWpAdmin\Hooks\Event;
    function call(Creuna\ObjectiveWpAdmin\AdminAdapter $admin, array $args): mixed;
}
```

The `Hook::event()` method is where we declare the actual WordPress hook to use. For that
we use a special `Event` object, which contains both the name of the hook and the _arity_
of the callback. WordPress expects a specification of how many arguments we expect to the
listener function, and that's what _arity_ stands for.

For instance, the `init` hook doesn't provide any arguments, so the arity is `0`, which
makes the `Event` look like this:

```php
new Event('init', 0);
```

Luckily, we've built in a bunch of those hooks as static methods on the `Event` class, so
you can just do this:

```php
Event::init();
```

## Actions and Filters
It isn't enough to know what hook to use. WordPress has two different kinds of hooks, and
we need to specify whether it's an `Action` or a `Filter`.

The only difference between the two is that `Filters` are supposed to return a manipulated
version of its arguments, and `Actions` are supposed to return `void`.

However, it's not unusual to see filters containing side effects, mutating the world (!).

We've tried to streamline this, and simply provide two subtypes of `Hook`; `Action` and
`Filter`. They don't change the interface in any way and are only used for a type check to
see whether to use WordPress' `add_action` or `add_filter` function.

Bottom line – here's what a stupid `init` action looks like:

```php
class SendEmailOnEveryRequestAction implements Action
{
    protected $mailer;

    public function __construct(SomeMailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function event()
    {
        return Event::init();
    }

    /**
     * $args is empty, because the arity of the `init` hook is 0.
     */
    public function call(AdminAdapter $admin, array $args)
    {
        $this->mailer->send('Your site was visited');
    }
}
```

A filter looks identical, but expects a return value.

```php
class MyFilter implements Filter
{
    ...
    public function call(AdminAdapter $admin, array $args)
    {
        list($argFromWordPress, $secondArgFromWordPress) = $args;

        return 'Something modified given the input args';
    }
}
```

Then you use the `Admin::hook()` method to add it to the system, passing in your
dependencies yourself, to preserve testability.

```php
$admin->hook(new SendEmailOnEveryRequestAction(new SomeMailer));
$admin->hook(new MyFilter);
```

You might not need to use these hooks, but they are used internally for features like
[Post Types](post-types.md).
