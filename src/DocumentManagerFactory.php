<?php


namespace LaravelDoctrine\ODM;


use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use LaravelDoctrine\ODM\Common\Config;
use LaravelDoctrine\ODM\Common\ConfigurationFactory;
use LaravelDoctrine\ODM\Common\ListenerRegistry;
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

	/**
	 * @var ListenerRegistry
	 */
	protected $listenerRegistry;

	public function __construct(ConfigurationFactory $configurationFactory, ConnectionManager $connectionManager, MetaDataManager $metaDataManager, CacheManager $cacheManager, ListenerRegistry $listenerRegistry)
	{
		$this->configurationFactory = $configurationFactory;
		$this->connectionManager = $connectionManager;
		$this->metaDataManager = $metaDataManager;
		$this->cacheManager = $cacheManager;
		$this->listenerRegistry = $listenerRegistry;
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

		/*
		 * Event Listeners and Subscribers
		 */
		$this->registerListeners($config, $manager);
		$this->registerSubscribers($config, $manager);

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

	public function registerListeners(Config $config, DocumentManager $manager)
	{
		$registrations = $config->getSetting('events.listeners') ?: [];

		foreach($registrations as $event => $listeners)
		{
			if(!is_array($listeners))
			{
				$listeners = [$listeners];
			}

			foreach($listeners as $listener)
			{
				$manager->getEventManager()->addEventListener($event, $this->listenerRegistry->getListener($listener));
			}
		}
	}

	public function registerSubscribers(Config $config, DocumentManager $manager)
	{
		$registrations = $config->getSetting('events.subscribers') ?: [];

		foreach($registrations as $event => $subscribers)
		{
			if(!is_array($subscribers))
			{
				$subscribers = [$subscribers];
			}

			foreach($subscribers as $subscriber)
			{
				$manager->getEventManager()->addEventListener($event, $this->listenerRegistry->getSubscriber($subscriber));
			}
		}
	}
}