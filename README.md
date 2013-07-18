# Doctrine repository service provider for Silex

This service provider exposes an easy way to have Repositories for your silex database.

## Requirements

This service provider has ben built to work with the `DoctrineServiceProvider` silex extension. See [Silex' DoctrineServiceProvider
documentation](http://silex.sensiolabs.org/doc/providers/doctrine.html) for instruction on how to use it.

## Installation

Add `insolis/repository-service-provider` to your `composer.json` file.

## Configuration

Register the service provider:

    $app->register(
        new Insolis\Provider\RepositoryServiceProvider(), array(
            'repository.repositories' => array(
                'projects' => 'MyProject\Repository\Project',
            )
        )
    );

The service provider expects parameter `repository.repositories` to be set and to be an associative array with service
names as keys and repository classes as values.

In the example above, the `projects` service will be exposed by Pimple (ie, you can access it through `$app['projects']`)
using the `MyProject\Repository\Project` class.

## Usage

As you might have guessed by now, you need to implement a concrete class for every repository that you want to use.
That repository must extend `Insolis\Repository` and implement the `getTableName` method, that should return the
database's table name bound to that repository.

In the example above, given your projects are stored in the `project` table, the `MyProject\Repository\Project` class
would look like that:

```php
<?php

namespace MyProject\Repository;

use Insolis\Repository;

class Project extends Repository;
{
    public function getTableName()
    {
        return 'project';
    }
}
```

The default repository implementation exposes a number of methods to manipulate your database that are basically proxies
to methods from `Doctrine\DBAL\Connection`:

### `insert(array $data)`

```php
$app['projects']->insert(array(
    'title'       => 'foo',
    'description' => 'A project'
));
```

Will insert a project in the table with `title` "foo" and `description` "A project".

### `update(array $data, array $identifier)`

```php
$app['projects']->update(array('title' => 'bar'), array('title' => 'foo'));
```

Will update all projects' `title` from "foo" to "bar".

### `delete(array $identifier)`

```php
$app['projects']->delete(array('title' => 'bar'));
```

Will update all projects which title is "bar".

### `find($id)`

```php
$app['projects']->find(42);
```

Returns the project which id field is 42.

### `findAll()`

```php
$app['projects']->findAll();
```

Returns the entire table content.

### Extending a repository

Extending a repository is as easy as adding methods to it. For example, you could add a `findByTitle($title)` method to
return all projects based on their title:

```php
public function findByTitle($title)
{
    return $this->db->fetchAll(sprintf("SELECT * FROM %s WHERE title = ?;', $this->getTableName()),
        array($title));
}
```

## Credits

* [KnpLabs](http://knplabs.com/) for original code
* [Joshua Morse](https://github.com/joshuamorse/) for initial code extraction
* [Gábor Fási](https://github.com/maerlyn/) for extended maintenance
