<?php


namespace CImrie\ODM;


use CImrie\ODM\Configuration\MetaData\MetaDataRegistry;
use CImrie\ODM\Logging\Logger;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Types\Type;
use CImrie\ODM\Common\Config;
use CImrie\ODM\Common\ConfigurationFactory;
use CImrie\ODM\Common\Registries\ListenerRegistry;
use CImrie\ODM\Configuration\Connections\ConnectionResolver;
use LaravelDoctrine\ORM\Configuration\Cache\CacheManager;

class DocumentManagerFactory {

	/**
	 * @var
	 */
	protected $configurationFactory;

	/**
	 * @var ConnectionResolver
	 */
	protected $connectionResolver;

	/**
	 * @var CacheManager
	 */
	protected $cacheManager;

	/**
	 * @var \CImrie\ODM\Common\Registries\ListenerRegistry
	 */
	protected $listenerRegistry;

    /**
     * @var MetaDataRegistry
     */
    protected $metadata;

    /**
     * @var Logger | null
     */
    protected $logger;

    public function __construct(ConfigurationFactory $configurationFactory, ConnectionResolver $connectionResolver, MetaDataRegistry $metadata, CacheManager $cacheManager = null, ListenerRegistry $listenerRegistry, Logger $logger = null)
	{
		$this->configurationFactory = $configurationFactory;
		$this->connectionResolver    = $connectionResolver;
		$this->cacheManager         = $cacheManager;
		$this->listenerRegistry     = $listenerRegistry;
        $this->metadata = $metadata;
        $this->logger = $logger;
    }

	public function create(Config $config)
	{
		$configuration = $this->configurationFactory->create();
		$connection    = $this->connectionResolver->resolve($config->getConnectionName());

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
		$this->setMetadataDriver($config, $configuration);

		/*
		 * Caching
		 */
		if($this->cacheManager)
        {
            $configuration->setMetadataCacheImpl($this->cacheManager->driver($config->getCacheDriver()));
        }

		/*
		 * Logging
		 */
		if($this->logger)
        {
            $configuration->setLoggerCallable($this->logger->closure());
        }

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

		/*
		 * Custom Types
		 */
		$this->registerMappingTypes($config);

		return $manager;
	}

	public function setMetadataDriver(Config $config, Configuration $configuration)
	{
	    $metadata = $this->metadata->get($config->getSetting('meta'));
        $driver = $metadata->resolve($config->getSettings());
	    $driverChain = new MappingDriverChain();

	    $driverChain->addDriver($driver, 'default');
	    $driverChain->setDefaultDriver($driver);

        $configuration->setMetadataDriverImpl($driverChain);
        $configuration->setClassMetadataFactoryName($metadata->getClassMetadataFactoryName());
	}

	/**
	 * @param Config $config
	 * @param Configuration $configuration
	 * @param DocumentManager $documentManager
	 */
	public function registerFilters(Config $config, Configuration $configuration, DocumentManager $documentManager)
	{
		if($filters = $config->getSetting('filters'))
		{
			foreach($filters as $name => $filter)
			{
				$configuration->addFilter($name, $filter);
				$documentManager->getFilterCollection()->enable($name);
			}
		}
	}

	/**
	 * @param Config $config
	 * @param DocumentManager $manager
	 */
	public function registerListeners(Config $config, DocumentManager $manager)
	{
		$registrations = $config->getSetting('events.listeners') ?: [];

		foreach($registrations as $event => $listeners)
		{
			if( ! is_array($listeners))
			{
				$listeners = [$listeners];
			}

			foreach($listeners as $listener)
			{
				$manager->getEventManager()->addEventListener($event, $this->listenerRegistry->getListener($listener));
			}
		}
	}

	/**
	 * @param Config $config
	 * @param DocumentManager $manager
	 */
	public function registerSubscribers(Config $config, DocumentManager $manager)
	{
		$registrations = $config->getSetting('events.subscribers') ?: [];

		foreach($registrations as $event => $subscribers)
		{
			if( ! is_array($subscribers))
			{
				$subscribers = [$subscribers];
			}

			foreach($subscribers as $subscriber)
			{
				$manager->getEventManager()->addEventListener($event, $this->listenerRegistry->getSubscriber($subscriber));
			}
		}
	}

	/**
	 * @param Config $config
	 *
	 * @throws \Doctrine\DBAL\DBALException If Database Type or Doctrine Type is not found.
	 */
	protected function registerMappingTypes(Config $config)
	{
		$types = $config->getSetting('mapping_types') ?: [];
		foreach($types as $dbType => $doctrineType)
		{
			if(!Type::hasType($dbType))
			{
				Type::addType($dbType, $doctrineType);
			}
		}
	}
}