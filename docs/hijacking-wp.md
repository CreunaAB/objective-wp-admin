[← Back to TOC](index.md)

# Hijacking WordPress
As mentioned in the [abstract](abstract.md), this package is not designed to play nice
with WordPress plugins. Nothing prevents you from adding plugins to the same site, but
this package purposefully removes a whole lot of noise from the default WP setup, so that
it can be added back gradually and declaratively.

## Resetting
The first step is resetting the environment. Doing that is simple.

```php
<?php

namespace Acme\Admin;

use Creuna\ObjectiveWpAdmin\Admin;

Admin::reset();
```

The admin UI is now cleared out to a bare minimum.

* **Dashboard is cleaned.** Any dashboard modules should be opt-in.
* **Comments are gone.** This is a feature that's rarely used at all. If it is, we want to
  manually add it back.
* **Taxonomies are gone.** Again, we want Tags and Categories to be opt-in.
* **Appearance is gone.** Users should not mess with the code. Ever.
* **Settings are gone.** Configuration is better as version controlled code.
* **Posts and Pages are gone.** We manually add the post types that match our data model.

Some things are kept in the menu.

* **Media Library** only manages uploads and is almost always used. If you don't want to
  use it you might as well go for something other than WordPress as a CMS.
* **Plugins** are left in place, because some of them actually do some nice, non
  destructive things, like forcing HTTPS or enabling regeneration of thumbnails.
* **Users** will always be an essential part of a CMS, and admins should have a way to
  overview them.
* **Tools** is a common target for plugin-generated pages, and we don't have any reason to
  remove it.

## Interacting
Now that we have a clean environment to work with, we can start fresh. But instead of
using the built in global functions that WordPress supplies, we use the `Admin` object
that we get back from calling `Admin::reset()`.

```php
use Creuna\ObjectiveWpAdmin\Admin;

$admin = Admin::reset();
```

Now we can use this `$admin` object that we got to interact with the CMS. Since you might
want to access this from different files, it might be a good idea to wrap it in a helper
function.

```php
use Creuna\ObjectiveWpAdmin\Admin;

$admin = Admin::reset();

function admin()
{
    global $admin;
    return $admin;
}
```

The most low level way to interact with the system is by
[manually adding hooks](hooks.md). The other capabilities like [post types](post-types.md)
all build on that foundation.
