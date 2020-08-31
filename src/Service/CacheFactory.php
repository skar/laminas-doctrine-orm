<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Doctrine\Common\Cache;
use Interop\Container\ContainerInterface;
use RuntimeException;

class CacheFactory extends AbstractFactory {
	/**
	 * @inheritDoc
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$class = $this->config['class'];

		if (!$class) {
			throw new RuntimeException('Cache must have a class name to instantiate');
		}

		$instance = $this->config['instance'];
		if (is_string($instance) && $container->has($instance)) {
			$instance = $container->get($instance);
		}

		if ($container->has($class)) {
			$cache = $container->get($class);
		} else {
			switch ($class) {
				case Cache\FilesystemCache::class:
					$cache = new $class($this->config['directory']);
					break;
				case Cache\ZendDataCache::class:
				case Cache\PredisCache::class:
					$cache = new $class($instance);
					break;
				default:
					$cache = new $class();
					break;
			}
		}

		if ($cache instanceof Cache\MemcacheCache) {
			$cache->setMemcache($instance);
		} elseif ($cache instanceof Cache\MemcachedCache) {
			$cache->setMemcached($instance);
		} elseif ($cache instanceof Cache\RedisCache) {
			$cache->setRedis($instance);
		}

		if ($cache instanceof Cache\CacheProvider) {
			$namespace = $this->config['namespace'];
			if ($namespace) {
				$cache->setNamespace($namespace);
			}
		}

		return $cache;
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'class'     => Cache\ArrayCache::class,
			'namespace' => '',
			'directory' => null,
			'instance'  => null,
		];
	}
}
