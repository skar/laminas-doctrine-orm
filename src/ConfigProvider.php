<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

class ConfigProvider {
	/**
	 * Retrieve configuration for laminas-diactoros.
	 *
	 * @return mixed[]
	 */
	public function __invoke() : array {
		return require __DIR__ . '/../config/module.config.php';
	}
}
