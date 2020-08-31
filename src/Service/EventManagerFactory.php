<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Interop\Container\ContainerInterface;
use Doctrine\Common\EventManager;

class EventManagerFactory extends AbstractFactory {
	/**
	 * @inheritDoc
	 *
	 * @return EventManager
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'subscribers' => [],
		];
	}
}
