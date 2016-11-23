[← Back to TOC](index.md)

# Installation
Install this package with Composer.

```shell
> composer require creuna/objective-wp-admin
```

## Recommended Architecture
Assuming you're familiar with Composer's autoloading, a good idea might be to add a PSR-4
namespace for your WordPress admin in `composer.json`.

```json
{
  "autoload": {
    "psr-4": {
      "Acme\\Admin\\": "app/Admin/"
    }
  }
}
```

> **Note:** If that `app` folder already is autoloaded as the `Acme` namespace, the above
> is redundant. Just make sure you have some namespace that is autoloaded which can hold
> all your admin configuration.

Once you've installed WordPress ([here's a good way to do that](
  https://medium.com/p/95d7a5a7ddd7) with Composer),
use a file that will be included on every admin page, like `functions.php`, to delegate to
your new directory.

```php
<?php // wordpress/wp-content/themes/your-theme/functions.php

// Autoload Composer dependencies
require_once(__DIR__.'/../../../../vendor/autoload.php');

// Get out of the clutches of WordPress
require_once(__DIR__.'/../../../../app/Admin/admin.php');
```

Next, we can create our admin script.

```php
<?php // app/Admin/admin.php

namespace Acme\Admin;

// ...
```

Now you're set up, and can move on to [hijacking your WP admin](hijacking-wp.md).
