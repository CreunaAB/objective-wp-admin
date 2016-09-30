# Object Oriented Abstraction of the WordPress Admin UI

## Abstract
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

## Usage
Install this package with composer. Right now, that can be done with a repository:

```json
{
  "repositories": [
    {
      "url": "https://github.com/CreunaAB/objective-wp-admin",
      "type": "git"
    }
  ],
  "require": {
    "creuna/objective-wp-admin": "dev-master"
  },
  "minimum-stability": "dev"
}
```

Once you've installed WordPress (again, [here's a good way to do that](https://medium.com/p/95d7a5a7ddd7) with Composer),
create a file that will be run on every request to the Admin pages.

```php
<?php // wordpress/wp-content/themes/your-theme/functions.php

// Autoload Composer dependencies
require_once(__DIR__.'/../../../../vendor/autoload.php');

// Get out of the clutches of WordPress
require_once(__DIR__.'/../../../../app/Admin/admin.php');
```

```php
<?php // app/Admin/admin.php

namespace Acme\Admin;

use Creuna\ObjectiveWpAdmin\Admin;

$admin = Admin::reset();
```

At this point, a lot of features will disappear from the interface, even Posts and Pages.
Now we can use this `$admin` object that we got to interact with the UI.

### Post Types
We tried to make the process of adding a custom post type feel like describing a database
table schema. It looks like this:

```php
<?php // app/Admin/PostTypes/Podcast.php

use Creuna\ObjectiveWpAdmin\Persistance\PostType;
use Creuna\ObjectiveWpAdmin\Persistance\Schema;

class Podcast implements PostType
{
    public function describe(Schema $schema) {
        $schema->string('url', 'URL')->required();
    }
}
```

Registering the PostType is trivial, and the menus and pages for managing
this resource is automatically added:

```php
<?php // app/Admin/admin.php

namespace Acme\Admin;

use Acme\Admin\PostTypes\Podcast;
use Creuna\ObjectiveWpAdmin\Admin;

$admin = Admin::reset();

$admin->registerType(Podcast::class);
```

#### Querying
We can then query the database by asking the `$admin` for a repository, like so:

```php
// ...

$podcasts = $admin->repository(Podcast::class);

// Get all podcasts
$podcasts->all();

// Get the 5 most recent podcasts
$podcasts->take(5)->all();

// Skip 5 podcasts, and get the 5 next ones (page 2)
$podcasts->skip(5)->take(5)->all();
```

### Hooks
The WordPress hooks system is a bit nasty. There is almost no consistency, and since WP is
a globally mutable mess, you have to make sure that some listeners are fired before others.

We've tried to clean that up. There are two interfaces, namely `Creuna\ObjectiveWpAdmin\Hooks\Action`
and `Creuna\ObjectiveWpAdmin\Hooks\Filter` available, that can be implemented in this way:

```php
<?php // app/Admin/Hooks/MyAction.php

namespace Acme\Admin\Hooks;

use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\AdminAdapter;

class MyAction implements Action
{
    public function event()
    {
        return 'init'; // Corresponds to the usual WordPress hooks
    }

    /**
     * @param AdminAdapter $admin
     *   This interface encapsulates the global mess and puts it
     *   in a testable interface, and every hook gets access to it.
     *
     * @param array $args
     *   Quite inconsistently, WordPress passes different arguments
     *   for different hooks. They are available in this $args array.
     */
    public function call(AdminAdapter $admin, array $args)
    {
        // ...
    }
}
```

And then you can add the hooks to the system like so:

```php
<?php // app/Admin/admin.php

namespace Acme\Admin;

use Acme\Admin\Hooks\MyAction;
use Creuna\ObjectiveWpAdmin\Admin;

$admin = Admin::reset();

$admin->hook(new MyAction);
```

The `Creuna\ObjectiveWpAdmin\Hooks\Filter` interface looks pretty much identical
to the `Action`, since they both extend the same `Hook` interface.
