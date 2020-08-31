<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Interop\Container\ContainerInterface;

class EntityManagerFactory extends AbstractFactory {
	/**
	 * {@inheritDoc}
	 *
	 * @return EntityManager
	 *
	 * @throws ORMException
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		return EntityManager::create(
			$container->get($this->getServiceName('connection')),
			$container->get($this->getServiceName('configuration'))
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'connection'    => 'orm_default',
			'configuration' => 'orm_default',
		];
	}
}
