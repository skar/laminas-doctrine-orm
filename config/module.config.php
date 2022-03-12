<?php
declare(strict_types=1);

use Doctrine\ORM;
use Doctrine\Migrations\Tools\Console\Command as DoctrineCommand;
use Interop\Container\ContainerInterface;
use Skar\LaminasDoctrineORM\Service;
use Skar\LaminasDoctrineORM\Command;
use Skar\Cache;

return [
	'dependencies' => [
		'factories' => [
			'doctrine.cache.array' => function(ContainerInterface $container) {
				return new Cache\Cache(new Cache\Storage\Adapter\Memory());
			},

			ORM\EntityManager::class => Service\EntityManagerFactory::class,

			DoctrineCommand\DiffCommand::class       => Command\MigrationsCommandFactory::class,
			DoctrineCommand\DumpSchemaCommand::class => Command\MigrationsCommandFactory::class,
			DoctrineCommand\ExecuteCommand::class    => Command\MigrationsCommandFactory::class,
			DoctrineCommand\GenerateCommand::class   => Command\MigrationsCommandFactory::class,
			DoctrineCommand\LatestCommand::class     => Command\MigrationsCommandFactory::class,
			DoctrineCommand\MigrateCommand::class    => Command\MigrationsCommandFactory::class,
			DoctrineCommand\RollupCommand::class     => Command\MigrationsCommandFactory::class,
			DoctrineCommand\StatusCommand::class     => Command\MigrationsCommandFactory::class,
			DoctrineCommand\UpToDateCommand::class   => Command\MigrationsCommandFactory::class,
			DoctrineCommand\VersionCommand::class    => Command\MigrationsCommandFactory::class,
		],
		'abstract_factories' => [
			Service\ServiceAbstractFactory::class,
		],
	],

	'laminas-cli' => [
		'commands' => [
			'doctrine:migrations:diff'        => DoctrineCommand\DiffCommand::class,
			'doctrine:migrations:dump-schema' => DoctrineCommand\DumpSchemaCommand::class,
			'doctrine:migrations:execute'     => DoctrineCommand\ExecuteCommand::class,
			'doctrine:migrations:generate'    => DoctrineCommand\GenerateCommand::class,
			'doctrine:migrations:latest'      => DoctrineCommand\LatestCommand::class,
			'doctrine:migrations:migrate'     => DoctrineCommand\MigrateCommand::class,
			'doctrine:migrations:rollup'      => DoctrineCommand\RollupCommand::class,
			'doctrine:migrations:status'      => DoctrineCommand\StatusCommand::class,
			'doctrine:migrations:up-to-date'  => DoctrineCommand\UpToDateCommand::class,
			'doctrine:migrations:version'     => DoctrineCommand\VersionCommand::class,
		],
	],

	'doctrine_factories' => [
		'entity_manager' => Service\EntityManagerFactory::class,
		'connection'     => Service\ConnectionFactory::class,
		'configuration'  => Service\ConfigurationFactory::class,
		'driver'         => Service\DriverFactory::class,
		'event_manager'  => Service\EventManagerFactory::class,
	],

	'doctrine' => [
		'migrations' => [
			'orm_default' => [
				'table_storage' => [
					'table_name'                 => 'migrations_executed',
					'version_column_name'        => 'version',
					'version_column_length'      => 255,
					'executed_at_column_name'    => 'executed_at',
					'execution_time_column_name' => 'execution_time',
				],
				'migrations_paths' => [
					'Skar\LaminasDoctrineORM' => 'data/migrations',
				],
				'all_or_nothing'          => true,
				'check_database_platform' => true,
			],
		],
	],
];
