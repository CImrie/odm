<?php


namespace LaravelDoctrine\ODM;


use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use LaravelDoctrine\ODM\Common\Config;
use LaravelDoctrine\ODM\Common\ConfigurationFactory;
use LaravelDoctrine\ORM\Configuration\Cache\CacheManager;
use LaravelDoctrine\ORM\Configuration\Connections\ConnectionManager;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaDataManager;

class DocumentManagerFactory {

	/**
	 * @var
	 */
	protected $configurationFactory;

	/**
	 * @var ConnectionManager
	 */
	protected $connectionManager;

	/**
	 * @var MetaDataManager
	 */
	protected $metaDataManager;

	/**
	 * @var CacheManager
	 */
	protected $cacheManager;

	public function __construct(ConfigurationFactory $configurationFactory, ConnectionManager $connectionManager, MetaDataManager $metaDataManager, CacheManager $cacheManager)
	{
		$this->configurationFactory = $configurationFactory;
		$this->connectionManager = $connectionManager;
		$this->metaDataManager = $metaDataManager;
		$this->cacheManager = $cacheManager;
	}

	public function create(Config $config)
	{
		$configuration = $this->configurationFactory->create();
		$connection = $this->connectionManager->driver($config->getConnectionName(), $config->getDriverResolvedConfig());

		/*
		 * Database
		 */
		$configuration->setDefaultDB($config->getDatabase());

		/*
		 * Proxies
		 */
		$configuration->setProxyDir($config->getSetting('proxies.path'));
		$configuration->setProxyNamespace($config->getSetting('proxies.namespace'));
		$configuration->setAutoGenerateProxyClasses($config->getSetting('proxies.auto_generate'));

		/*
		 * Hydrators
		 */
		$configuration->setHydratorDir($config->getSetting('hydrators.path'));
		$configuration->setHydratorNamespace($config->getSetting('hydrators.namespace'));
		$configuration->setAutoGenerateHydratorClasses($config->getSetting('hydrators.auto_generate'));

		/*
		 * Metadata Drivers
		 */
		$driver = $this->metaDataManager->driver($config->getSetting('meta'));
		$configuration->setMetadataDriverImpl($driver);

		/*
		 * Caching
		 */

		/*
		 * Manager is now ready for instantiation
		 */
		$manager = DocumentManager::create($connection, $configuration);

		/*
		 * Post-instantiation configuration.
		 *
		 * Filters
		 */
		$this->registerFilters($config, $configuration, $manager);

		return $manager;
	}

	public function registerFilters(Config $config, Configuration $configuration, DocumentManager $documentManager)
	{
		if ($filters = $config->getSetting('filters')) {
			foreach ($filters as $name => $filter) {
				$configuration->addFilter($name, $filter);
				$documentManager->getFilterCollection()->enable($name);
			}
		}
	}
}