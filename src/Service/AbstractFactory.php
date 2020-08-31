<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Stdlib\ArrayUtils;

abstract class AbstractFactory implements FactoryInterface {
	protected array $config;

	/**
	 * AbstractFactory constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config = []) {
		$this->config = ArrayUtils::merge($this->getDefaultConfig(), $config);
	}

	/**
	 * Returns service full name
	 *
	 * @param string $type
	 * @param string|null $key
	 *
	 * @return string
	 */
	protected function getServiceName($type, $key = null) {
		return sprintf(
			'doctrine.%s.%s',
			$type,
			($key === null) ? $this->config[$type] : $key
		);
	}

	/**
	 * Returns default config
	 *
	 * @return array
	 */
	abstract protected function getDefaultConfig(): array;
}
