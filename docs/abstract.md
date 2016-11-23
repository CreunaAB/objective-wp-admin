[← Back to TOC](index.md)

# Abstract
Let's face it; there are problems with the WordPress architecture. Yet, it's not uncommon
that clients specifically ask for WordPress as their content management GUI. Most likely
they've used it before, or they have access to a more substantial amount of learning
materials for WP than for other systems.

The PHP community at large have changed a lot over the last few years, and the common
convention nowadays is a much more Object Oriented style than the procedural style
that WordPress is written in.

At Creuna we've started experimenting with using WordPress not as it was intended,
but rather as a dependency that simply acts as a data source and admin UI.

Abstracting away the WordPress theme is easy enough, and interacting with WordPress as
a data source for posts can easily be done. But you're still going to have to interact
with the WP architecture to customize the Admin UI.

> Read about how we abstracted away WordPress [here](https://medium.com/p/95d7a5a7ddd7).

This package provides an OOP style API for interacting with the WP Admin UI in a very
opinionated way. You won't be able to do everything, but you might not want to do that
anyway.

> **Note:** This is not a WordPress plugin. It basically messes up everything about
> the default WordPress structure. It is designed to remove a lot of functionality
> that we don't use. It's not designed to play well with others.
