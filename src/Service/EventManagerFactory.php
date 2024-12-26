<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Interop\Container\ContainerInterface;
use Doctrine\Common\EventManager;
use InvalidArgumentException;

class EventManagerFactory extends AbstractFactory {
	/**
	 * @inheritDoc
	 *
	 * @return EventManager
	 */
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): EventManager {
		$eventManager = new EventManager();

		foreach ($this->config['subscribers'] as $subscriber) {
			if (!$container->has($subscriber)) {
				throw new InvalidArgumentException(sprintf('Subscriber "%s" not found', $subscriber));
			}
			$eventManager->addEventSubscriber($container->get($subscriber));
		}

		foreach ($this->config['listeners'] as $listener) {
			if (!$container->has($listener['listener'])) {
				throw new InvalidArgumentException(sprintf('Listeners "%s" not found', $listener));
			}
			$eventManager->addEventListener($listener['events'], $container->get($listener['listener']));
		}

		return $eventManager;
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'subscribers' => [],
			'listeners'   => [],
		];
	}
}
