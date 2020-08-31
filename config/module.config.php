<?php
declare(strict_types=1);

use Doctrine\ORM;
use Skar\LaminasDoctrineORM\Service;

return [
	'dependencies' => [
		'factories' => [
			ORM\EntityManager::class => Service\EntityManagerFactory::class,
		],
		'abstract_factories' => [
			Service\ServiceAbstractFactory::class,
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
	],
];
