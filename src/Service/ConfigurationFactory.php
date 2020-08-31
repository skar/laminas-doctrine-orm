<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Service;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Cache\CacheConfiguration;
use Doctrine\ORM\Cache\DefaultCacheFactory;
use Doctrine\ORM\Cache\RegionsConfiguration;
use Doctrine\ORM\Mapping\EntityListenerResolver;
use Interop\Container\ContainerInterface;
use \InvalidArgumentException;

class ConfigurationFactory extends AbstractFactory {
	/**
	 * @inheritDoc
	 *
	 * @return Configuration
	 *
	 * @throws DBALException
	 * @throws \Doctrine\ORM\ORMException
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$configuration = new Configuration();

		$configuration->setAutoGenerateProxyClasses($this->config['generate_proxies']);
		$configuration->setProxyDir($this->config['proxy_dir']);
		$configuration->setProxyNamespace($this->config['proxy_namespace']);

		$configuration->setEntityNamespaces($this->config['entity_namespaces']);

		$configuration->setCustomDatetimeFunctions($this->config['datetime_functions']);
		$configuration->setCustomStringFunctions($this->config['string_functions']);
		$configuration->setCustomNumericFunctions($this->config['numeric_functions']);

		$configuration->setClassMetadataFactoryName($this->config['class_metadata_factory_name']);

		foreach ($this->config['named_queries'] as $name => $query) {
			$configuration->addNamedQuery($name, $query);
		}

		foreach ($this->config['named_native_queries'] as $name => $query) {
			$configuration->addNamedNativeQuery($name, $query['sql'], new $query['rsm']());
		}

		foreach ($this->config['custom_hydration_modes'] as $name => $query) {
			$configuration->addCustomHydrationMode($name, $query);
		}

		foreach ($this->config['filters'] as $name => $class) {
			$configuration->addFilter($name, $class);
		}

		$configuration->setMetadataCacheImpl($container->get(
			$this->getServiceName('cache', $this->config['metadata_cache'])
		));
		$configuration->setQueryCacheImpl($container->get(
			$this->getServiceName('cache', $this->config['query_cache'])
		));
		$configuration->setResultCacheImpl($container->get(
			$this->getServiceName('cache', $this->config['result_cache'])
		));
		$configuration->setHydrationCacheImpl($container->get(
			$this->getServiceName('cache', $this->config['hydration_cache'])
		));

		$configuration->setMetadataDriverImpl($container->get($this->getServiceName('driver')));

		if ($namingStrategy = $this->config['naming_strategy']) {
			if (is_string($namingStrategy)) {
				if (!$container->has($namingStrategy)) {
					throw new InvalidArgumentException(sprintf('Naming strategy "%s" not found', $namingStrategy));
				}

				$configuration->setNamingStrategy($container->get($namingStrategy));
			} else {
				$configuration->setNamingStrategy($namingStrategy);
			}
		}

		if ($quoteStrategy = $this->config['quote_strategy']) {
			if (is_string($quoteStrategy)) {
				if (! $container->has($quoteStrategy)) {
					throw new InvalidArgumentException(sprintf('Quote strategy "%s" not found', $quoteStrategy));
				}

				$configuration->setQuoteStrategy($container->get($quoteStrategy));
			} else {
				$configuration->setQuoteStrategy($quoteStrategy);
			}
		}

		if ($repositoryFactory = $this->config['repository_factory']) {
			if (is_string($repositoryFactory)) {
				if (! $container->has($repositoryFactory)) {
					throw new InvalidArgumentException(sprintf('Repository factory "%s" not found', $repositoryFactory));
				}

				$configuration->setRepositoryFactory($container->get($repositoryFactory));
			} else {
				$configuration->setRepositoryFactory($repositoryFactory);
			}
		}

		if ($entityListenerResolver = $this->config['entity_listener_resolver']) {
			if ($entityListenerResolver instanceof EntityListenerResolver) {
				$configuration->setEntityListenerResolver($entityListenerResolver);
			} else {
				$configuration->setEntityListenerResolver($container->get($entityListenerResolver));
			}
		}

		if ($this->config['second_level_cache']['enabled']) {
			$regionsConfig = new RegionsConfiguration(
				$this->config['second_level_cache']['default_lifetime'],
				$this->config['second_level_cache']['default_lock_lifetime']
			);

			foreach ($this->config['second_level_cache']['regions'] as $regionName => $regionConfig) {
				if (array_key_exists('lifetime', $regionConfig)) {
					$regionsConfig->setLifetime($regionName, $regionConfig['lifetime']);
				}

				if (array_key_exists('lock_lifetime', $regionConfig)) {
					$regionsConfig->setLockLifetime($regionName, $regionConfig['lock_lifetime']);
				}
			}

			$cacheFactory = new DefaultCacheFactory($regionsConfig, $configuration->getResultCacheImpl());
			$cacheFactory->setFileLockRegionDirectory($this->config['second_level_cache']['file_lock_region_directory']);

			$cacheConfiguration = new CacheConfiguration();
			$cacheConfiguration->setCacheFactory($cacheFactory);
			$cacheConfiguration->setRegionsConfiguration($regionsConfig);

			$configuration->setSecondLevelCacheEnabled(true);
			$configuration->setSecondLevelCacheConfiguration($cacheConfiguration);
		}

		if ($filterSchemaAssetsExpression = $this->config['filter_schema_assets_expression']) {
			$configuration->setFilterSchemaAssetsExpression($filterSchemaAssetsExpression);
		}

		if ($className = $this->config['default_repository_class_name']) {
			$configuration->setDefaultRepositoryClassName($className);
		}

		if ($this->config['sql_logger']) {
			$configuration->setSQLLogger($container->get(
				$this->getServiceName($this->config['sql_logger'])
			));
		}

		foreach ($this->config['types'] as $name => $class) {
			if (Type::hasType($name)) {
				Type::overrideType($name, $class);
			} else {
				Type::addType($name, $class);
			}
		}

		return $configuration;
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultConfig(): array {
		return [
			'metadata_cache'                  => 'array',
			'query_cache'                     => 'array',
			'result_cache'                    => 'array',
			'hydration_cache'                 => 'array',
			'driver'                          => 'orm_default',
			'generate_proxies'                => true,
			'proxy_dir'                       => 'data/cache/DoctrineORM/Proxy',
			'proxy_namespace'                 => 'DoctrineORM\Proxy',
			'entity_namespaces'               => [],
			'datetime_functions'              => [],
			'string_functions'                => [],
			'numeric_functions'               => [],
			'filters'                         => [],
			'named_queries'                   => [],
			'named_native_queries'            => [],
			'custom_hydration_modes'          => [],
			'types'                           => [],
			'naming_strategy'                 => null,
			'quote_strategy'                  => null,
			'default_repository_class_name'   => null,
			'repository_factory'              => null,
			'class_metadata_factory_name'     => null,
			'entity_listener_resolver'        => null,
			'sql_logger'                      => null,
			'second_level_cache'              => [
				'enabled'                    => false,
				'default_lifetime'           => 3600,
				'default_lock_lifetime'      => 60,
				'file_lock_region_directory' => 'data/cache/DoctrineORM',
				'regions'                    => [],
			],
			'filter_schema_assets_expression' => null,
		];
	}
}
