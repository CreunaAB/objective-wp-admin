[← Back to TOC](index.md)

# Post Types
We tried to make the process of adding a custom post type feel like describing a database
table schema. It looks like this:

```php
<?php // app/Admin/PostTypes/Podcast.php

use Creuna\ObjectiveWpAdmin\Persistence\PostType;
use Creuna\ObjectiveWpAdmin\Persistence\Schema;

class Podcast implements PostType
{
    public function describe(Schema $schema)
    {
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

## Schema
The Schema Builder that is received in the `describe` method on the post type, has a very
descriptive API, that directly reflects the fields that are available on the edit page.

### `title`
This method adds back the title field to the page, along with the slug editor.

```php
$schema->title();
```

### `body`
This method adds back the content editor to the page.

```php
$schema->body();
```

### `string`
This adds a simple text field to the page. In the example below, `myField` will be the name of
the field, and `My Field` will be the label text. The second parameter is optional, and will
default to uppercasing the name of the field (`name -> Name`).

```php
$schema->string('myField', 'My Field');
```

## Querying
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

Queries are immutable, so every constraint like `take` returns a new instance of the
`Repository`. This way, you can store intermediate queries and reuse them.

```php
$newPodcasts = $podcasts->after('-1 month');

$okForKids = $newPodcasts->where('explicit', '=', false);
$upAndComing = $newPodcasts->where('rating', '>', 3.0);
```
