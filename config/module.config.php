<?php
declare(strict_types=1);

use Doctrine\ORM;
use Skar\LaminasDoctrineORM\Service;
use Skar\LaminasDoctrineORM\Command;

return [
	'dependencies' => [
		'factories' => [
			ORM\EntityManager::class => Service\EntityManagerFactory::class,

			Command\MigrationsDiff::class       => Command\MigrationsCommandFactory::class,
			Command\MigrationsDumpSchema::class => Command\MigrationsCommandFactory::class,
			Command\MigrationsExecute::class    => Command\MigrationsCommandFactory::class,
			Command\MigrationsGenerate::class   => Command\MigrationsCommandFactory::class,
			Command\MigrationsLatest::class     => Command\MigrationsCommandFactory::class,
			Command\MigrationsMigrate::class    => Command\MigrationsCommandFactory::class,
			Command\MigrationsRollup::class     => Command\MigrationsCommandFactory::class,
			Command\MigrationsStatus::class     => Command\MigrationsCommandFactory::class,
			Command\MigrationsUpToDate::class   => Command\MigrationsCommandFactory::class,
			Command\MigrationsVersion::class    => Command\MigrationsCommandFactory::class,
		],
		'abstract_factories' => [
			Service\ServiceAbstractFactory::class,
		],
	],

	'laminas-cli' => [
		'commands' => [
			'doctrine:migration:diff'        => Command\MigrationsDiff::class,
			'doctrine:migration:dump-schema' => Command\MigrationsDumpSchema::class,
			'doctrine:migration:execute'     => Command\MigrationsExecute::class,
			'doctrine:migration:generate'    => Command\MigrationsGenerate::class,
			'doctrine:migration:latest'      => Command\MigrationsLatest::class,
			'doctrine:migration:migrate'     => Command\MigrationsMigrate::class,
			'doctrine:migration:rollup'      => Command\MigrationsRollup::class,
			'doctrine:migration:status'      => Command\MigrationsStatus::class,
			'doctrine:migration:up-to-date'  => Command\MigrationsUpToDate::class,
			'doctrine:migration:version'     => Command\MigrationsVersion::class,
		],
	],

	'doctrine_factories' => [
		'entity_manager' => Service\EntityManagerFactory::class,
		'connection'     => Service\ConnectionFactory::class,
		'configuration'  => Service\ConfigurationFactory::class,
		'driver'         => Service\DriverFactory::class,
		'cache'          => Service\CacheFactory::class,
		'event_manager'  => Service\EventManagerFactory::class,
	],

	'doctrine' => [
		'migrations' => [
			'name'      => 'Doctrine Database Migrations',
			'namespace' => 'Skar\LaminasDoctrineORM',
			'directory' => 'data/migrations',
			'table'     => 'migrations',
			'column'    => 'version',
		],
	],
];
