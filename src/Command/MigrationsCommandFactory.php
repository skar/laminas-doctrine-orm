<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\AbstractCommand;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MigrationsCommandFactory implements FactoryInterface {
	/**
	 * @inheritDoc
	 *
	 * @return AbstractCommand
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		return new $requestedName($container);
	}
}
