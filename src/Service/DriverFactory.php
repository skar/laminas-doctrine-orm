<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Interop\Container\ContainerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\Persistence\Mapping\Driver\FileDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\Driver\AnnotationDriver;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Annotations\IndexedReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Laminas\Stdlib\ArrayUtils;
use \InvalidArgumentException;

class DriverFactory extends AbstractFactory {
	/**
	 * @inheritDoc
	 *
	 * @return MappingDriver
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		return $this->createDriver($this->config, $container);
	}

	/**
	 * @param array $config
	 * @param ContainerInterface $container
	 *
	 * @return MappingDriver
	 */
	protected function createDriver(array $config, ContainerInterface $container) {
		if (!$class = $config['class']) {
			throw new InvalidArgumentException('Drivers must specify a class');
		}

		if (!class_exists($class)) {
			throw new InvalidArgumentException(sprintf('Driver with type "%s" could not be found', $class));
		}

		if ($class === AttributeDriver::class || is_subclass_of($class, AttributeDriver::class)) {
			// AttributeDriver extends AnnotationDriver in violation of the Liskov substitution principle
			$driver = new $class($config['paths']);
		} elseif ($class === AnnotationDriver::class || is_subclass_of($class, AnnotationDriver::class)) {
			// Special options for AnnotationDrivers.
			$reader = new PsrCachedReader(
				new IndexedReader(new AnnotationReader()),
				$container->get($this->getServiceName('cache'))
			);
			$driver = new $class($reader, $config['paths']);
		} else {
			$driver = new $class($config['paths']);
		}

		if ($config['extension'] && $driver instanceof FileDriver) {
			$locator = $driver->getLocator();

			if (get_class($locator) !== DefaultFileLocator::class) {
				throw new InvalidArgumentException(sprintf(
					'Discovered file locator for driver of type "%s" is an instance of "%s". This factory '
					. 'supports only the DefaultFileLocator when an extension is set for the file locator',
					get_class($driver),
					get_class($locator)
				));
			}

			$driver->setLocator(new DefaultFileLocator($locator->getPaths(), $config['extension']));
		}

		// Extra post-create options for DriverChain.
		if ($driver instanceof MappingDriverChain && $config['drivers']) {
			foreach ($config['drivers'] as $namespace => $driverConfig) {
				$driverConfig = ArrayUtils::merge($this->getDefaultConfig(), $driverConfig);
				$driver->addDriver($this->createDriver($driverConfig, $container), $namespace);
			}
		}

		return $driver;
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'class'     => MappingDriverChain::class,
			'paths'     => [],
			'cache'     => 'array',
			'extension' => null,
			'drivers'   => [],
		];
	}
}
