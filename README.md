# Laminas Doctrine ORM

Simple Laminas/Mezzio Doctrine ORM integration.

Please feel free to report bugs and missing features.

# Using

## Configuration

Create config file `config/autoload/doctrine.global.php` with minimal config:

* `doctrine` - Key for doctrine config
    * `connection`
        * `orm_default`
            * `driver_class` - The full name of `\Doctrine\DBAL\Driver` implementation
            * `params` - The connection driver [parameters](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html)
    * `driver` - Mapping driver configuration
        * `orm_default`
            * `drivers`
                * Key must be a namespace for entities (e.g. `App\Entity`)
                    * `class` - The full name of [the metadata driver](https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/metadata-drivers.html#metadata-drivers)
                    * `paths` - An array of directories containing your [entities](https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/basic-mapping.html#basic-mapping)
                
See [the Doctrine documentation](https://www.doctrine-project.org/projects/doctrine-dbal/en/2.7/reference/configuration.html)
for more possible configurations. 

### Example Configuration
```php
<?php
declare(strict_types=1);

use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driver_class' => Driver::class,
                'params' => [
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => 'root',
                    'dbname'   => 'database',
                ],
            ],
        ],
        
        'driver' => [
            'orm_default' => [
                'drivers' => [
                    'App\Entity' => [
                        'class' => AnnotationDriver::class,
                        'paths' => [
                            'src/Entity',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
```

## Migrations Commands

Commands uses Laminas CLI and `vendor/bin/laminas` entry point for it

### Possible commands
```
doctrine:migrations:diff         [diff] Generate a migration by comparing your current database to your mapping information.
doctrine:migrations:dump-schema  [dump-schema] Dump the schema for your database to a migration.
doctrine:migrations:execute      [execute] Execute a single migration version up or down manually.
doctrine:migrations:generate     [generate] Generate a blank migration class.
doctrine:migrations:latest       [latest] Outputs the latest version number
doctrine:migrations:migrate      [migrate] Execute a migration to a specified version or the latest available version.
doctrine:migrations:rollup       [rollup] Rollup migrations by deleting all tracked versions and insert the one version that exists.
doctrine:migrations:status       [status] View the status of a set of migrations.
doctrine:migrations:up-to-date   [up-to-date] Tells you if your schema is up-to-date.
doctrine:migrations:version      [version] Manually add and delete migration versions from the version table.
```

### Example
```
$ vendor/bin/laminas doctrine:migrations:diff
$ vendor/bin/laminas doctrine:migrations:migrate
```

# TODO

1. Tests
2. Split this library into separate DBAL, ORM and Migrations libraries
3. Add libraries support (auth, pagination etc)

# WHY

Because [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule/) is only for Laminas (full MVC) but I need
simple integration for Mezzio.
