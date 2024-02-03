<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerInterface;

class EntityManagerFactory extends AbstractFactory {
	/**
	 * {@inheritDoc}
	 *
	 * @return EntityManager
	 *
	 * @throws ORMException
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null): EntityManager {
		return new EntityManager(
			$container->get($this->getServiceName('connection')),
			$container->get($this->getServiceName('configuration')),
			$container->get($this->getServiceName('event_manager'))
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'connection'    => 'orm_default',
			'configuration' => 'orm_default',
			'event_manager' => 'orm_default',
		];
	}
}
