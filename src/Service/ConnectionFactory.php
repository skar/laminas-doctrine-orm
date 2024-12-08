<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Doctrine\DBAL;
use Interop\Container\ContainerInterface;

class ConnectionFactory extends AbstractFactory {
	/**
	 * @inheritDoc
	 *
	 * @return DBAL\Connection
	 *
	 * @throws DBAL\Exception
	 */
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DBAL\Connection {
		$params = [
			'driverClass'  => $this->config['driver_class'],
			'wrapperClass' => $this->config['wrapper_class'],
			'pdo'          => is_string($this->config['pdo']) ? $container->get($this->config['pdo']) : $this->config['pdo'],
		];

		$connection = DBAL\DriverManager::getConnection(
			array_merge($params, $this->config['params']),
			$container->get($this->getServiceName('configuration')),
			$container->get($this->getServiceName('event_manager'))
		);

		$platform = $connection->getDatabasePlatform();
		foreach ($this->config['doctrine_type_mappings'] as $dbType => $doctrineType) {
			$platform->registerDoctrineTypeMapping($dbType, $doctrineType);
		}
		foreach ($this->config['doctrine_commented_types'] as $doctrineType) {
			$platform->markDoctrineTypeCommented($doctrineType);
		}

		return $connection;
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'configuration'            => 'orm_default',
			'event_manager'            => 'orm_default',
			'pdo'                      => null,
			'driver_class'             => null,
			'wrapper_class'            => null,
			'params'                   => [],
			'doctrine_type_mappings'   => [],
			'doctrine_commented_types' => [],
			'use_save_points'          => false,
		];
	}
}
