<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Stdlib\ArrayUtils;

abstract class AbstractFactory implements FactoryInterface {
	protected $config;

	/**
	 * AbstractFactory constructor.
	 *
	 * @param array $config
	 */
	public function __construct(array $config = []) {
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
	protected function getServiceName(string $type, ?string $key = null): string {
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
