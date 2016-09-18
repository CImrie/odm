<?php

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Query\FilterCollection;
use Doctrine\ODM\MongoDB\Repository\RepositoryFactory;
use Doctrine\ODM\MongoDB\Types\Type;
use LaravelDoctrine\ODM\Common\Config;
use LaravelDoctrine\ODM\Common\ConfigurationFactory;
use LaravelDoctrine\ODM\Common\Registries\ListenerRegistry;
use LaravelDoctrine\ODM\Configuration\Connections\ConnectionManager;
use LaravelDoctrine\ODM\Configuration\MetaData\Annotations;
use LaravelDoctrine\ODM\Configuration\MetaData\MetaDataManager;
use LaravelDoctrine\ODM\DocumentManagerFactory;
use LaravelDoctrine\ORM\Configuration\Cache\CacheManager;
use Mockery as m;
use Mockery\Mock as Mock;

class DocumentManagerFactoryTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var DocumentManagerFactory
	 */
	protected $factory;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Configuration | Mock
	 */
	protected $ODMConfiguration;

	/**
	 * @var MappingDriver | Mock
	 */
	protected $mappingDriver;

	/**
	 * @var array
	 */
	protected $settings = [
		'meta'       => 'annotations',
		'connection' => 'mongodb',
		'paths'      => [
			'Entities',
		],
		'repository' => 'Repo',
		'proxies'    => [
			'namespace'     => 'Proxies',
			'path'          => 'dir',
			'auto_generate' => false,
		],
		'hydrators'  => [
			'namespace'     => 'Hydrators',
			'path'          => 'hydrator_dir',
			'auto_generate' => false,
		],
	];

	protected $databaseConnectionsSettings = [
		'mongodb' => [
			'driver'   => 'mongodb',
			'host'     => 'localhost',
			'port'     => 27017,
			'database' => 'dbname',
			'username' => 'user',
			'password' => 'pass',
		],
	];

	protected $configurationFactory;

	/**
	 * @var ConnectionManager | Mock
	 */
	protected $connectionManager;

	/**
	 * @var MetaDataManager | Mock
	 */
	protected $metadataManager;

	/**
	 * @var CacheManager | Mock
	 */
	protected $cacheManager;

	/**
	 * @var \LaravelDoctrine\ODM\Common\Registries\ListenerRegistry | Mock
	 */
	protected $listenerRegistry;

	protected function setUp()
	{
		$this->updateConfig();
		$this->mockODMConfiguration();
		$this->mockMetadata();
		$this->mockCacheManager();
		$this->mockODMConfigurationFactory();

		$this->listenerRegistry = m::mock(ListenerRegistry::class);
		$this->setUpFactory();
	}

	public function assertDocumentManager(DocumentManager $manager)
	{
		$this->assertInstanceOf(DocumentManager::class, $manager);
		$this->assertInstanceOf(Connection::class, $manager->getConnection());
		$this->assertInstanceOf(Configuration::class, $manager->getConfiguration());
	}

	public function test_manager_gets_instantiated()
	{
		$manager = $this->factory->create($this->config);
		$this->assertDocumentManager($manager);
	}

	public function test_can_enable_filters()
	{
		$this->settings['filters'] = [
			'name' => OdmFilterStub::class,
		];

		$this->ODMConfiguration->shouldReceive('addFilter')
		                       ->with('name', OdmFilterStub::class)
		                       ->once();

		$this->ODMConfiguration->shouldReceive('getFilterClassName')
		                       ->atLeast()->once()->andReturn(OdmFilterStub::class);

		$this->ODMConfiguration->shouldReceive('getFilterParameters')
		                       ->atLeast()->once()->andReturn([]);

		$this->updateConfig();
		$manager = $this->factory->create($this->config);

		$this->assertDocumentManager($manager);
		$this->assertInstanceOf(FilterCollection::class, $manager->getFilterCollection());
		$this->assertContains('name', array_keys($manager->getFilterCollection()->getEnabledFilters()));
	}

	public function test_can_register_event_listeners()
	{
		$this->settings['events']['listeners'] = [
			'name' => OdmListenerStub::class,
		];

		$this->updateConfig();
		$this->mockListenerRegistryListeners();

		$this->factory = new DocumentManagerFactory(
			$this->configurationFactory,
			$this->connectionManager,
			$this->metadataManager,
			$this->cacheManager,
			$this->listenerRegistry
		);

		$manager = $this->factory->create($this->config);

		$this->assertDocumentManager($manager);
		$this->assertCount(1, $manager->getEventManager()->getListeners());
		$this->assertContains('name', array_keys($manager->getEventManager()->getListeners()));
	}

	public function test_can_register_event_subscribers()
	{
		$this->settings['events']['subscribers'] = [
			'name' => OdmSubscriberStub::class,
		];

		$this->updateConfig();
		$this->mockListenerRegistrySubscribers();

		$this->factory = new DocumentManagerFactory(
			$this->configurationFactory,
			$this->connectionManager,
			$this->metadataManager,
			$this->cacheManager,
			$this->listenerRegistry
		);

		$manager = $this->factory->create($this->config);

		$this->assertDocumentManager($manager);
		$this->assertCount(1, $manager->getEventManager()->getListeners());
		$this->assertContains('name', array_keys($manager->getEventManager()->getListeners()));
	}

	public function test_can_load_custom_types()
	{
		$count = count(Type::getTypesMap());
		$this->settings['odm_mapping_types'] = [
			'mytype' => m::mock(Type::class)
		];

		$this->updateConfig();

		$manager = $this->factory->create($this->config);

		$this->assertDocumentManager($manager);
		$this->assertCount($count + 1, Type::getTypesMap());
		$this->assertContains('mytype', array_keys(Type::getTypesMap()));
	}

//	public function test_can_load_odm_metadata_driver()
//	{
//		$driver = m::mock(AnnotationDriver::class);
//		$driver->shouldReceive('getClassMetadataFactoryName')
//			->andReturn(ClassMetadataFactory::class);
//
//		$mappingDriver = m::mock(Annotations::class);
//		$mappingDriver->shouldReceive('resolve')
//			->andReturn();
//
//		$this->mappingDriver = $driver;
//
//		$this->metadataManager = m::mock(MetaDataManager::class);
//		$this->metadataManager->shouldReceive('driver')
//		                      ->once()
//		                      ->andReturn($this->mappingDriver);
//
//		$factory = new DocumentManagerFactory(
//			$this->configurationFactory,
//			$this->connectionManager,
//			$this->metadataManager,
//			$this->cacheManager,
//			$this->listenerRegistry
//		);
//
//		$manager = $factory->create($this->config);
//		$this->assertDocumentManager($manager);
//	}

	public function mockODMConfiguration()
	{
		$this->ODMConfiguration = m::mock(Configuration::class);

		/*
		 * Test can configure proxies
		 */
		$this->ODMConfiguration->shouldReceive('setProxyDir')
		                       ->once()
		                       ->with($this->settings['proxies']['path']);

		$this->ODMConfiguration->shouldReceive('getProxyDir')
		                       ->atLeast()->once()
		                       ->andReturn($this->settings['proxies']['path']);

		$this->ODMConfiguration->shouldReceive('setProxyNamespace')
		                       ->once()
		                       ->with($this->settings['proxies']['namespace']);

		$this->ODMConfiguration->shouldReceive('getProxyNamespace')
		                       ->andReturn($this->settings['proxies']['namespace']);

		$this->ODMConfiguration->shouldReceive('setAutoGenerateProxyClasses')
		                       ->once()
		                       ->with($this->settings['proxies']['auto_generate']);

		$this->ODMConfiguration->shouldReceive('getAutoGenerateProxyClasses')
		                       ->andReturn($this->settings['proxies']['auto_generate']);

		/*
		 * Test can configure Hydrators
		 */
		$this->ODMConfiguration->shouldReceive('setHydratorDir')
		                       ->atLeast()->once()
		                       ->with($this->settings['hydrators']['path']);

		$this->ODMConfiguration->shouldReceive('getHydratorDir')
		                       ->atLeast()->once()
		                       ->andReturn($this->settings['hydrators']['path']);

		$this->ODMConfiguration->shouldReceive('setHydratorNamespace')
		                       ->atLeast()->once()
		                       ->with($this->settings['hydrators']['namespace']);

		$this->ODMConfiguration->shouldReceive('getHydratorNamespace')
		                       ->atLeast()->once()
		                       ->andReturn($this->settings['hydrators']['namespace']);

		$this->ODMConfiguration->shouldReceive('setAutoGenerateHydratorClasses')
		                       ->atLeast()->once()
		                       ->with($this->settings['hydrators']['auto_generate']);


		$this->ODMConfiguration->shouldReceive('getAutoGenerateHydratorClasses')
		                       ->atLeast()->once()
		                       ->andReturn(false);

		/*
		 * Test can configure MetaData driver
		 */
		$this->ODMConfiguration->shouldReceive('setMetadataDriverImpl')
		                       ->once();

		$this->ODMConfiguration->shouldReceive('getClassMetadataFactoryName')
		                       ->atLeast()->once()
		                       ->andReturn(ClassMetadataFactory::class);

		$cache = m::mock(Cache::class);
		$this->ODMConfiguration->shouldReceive('getMetadataCacheImpl')
		                       ->atLeast()->once()
		                       ->andReturn($cache);

		/*
		 * Test Default DB
		 */
		$this->ODMConfiguration->shouldReceive('setDefaultDB')
		                       ->once()
		                       ->with($this->databaseConnectionsSettings['mongodb']['database']);

		/*
		 * Misc wiring
		 */
		$repoFactory = m::mock(RepositoryFactory::class);
		$this->ODMConfiguration->shouldReceive('getRepositoryFactory')
		                       ->andReturn($repoFactory);
	}

	public function mockODMConfigurationFactory()
	{
		$this->configurationFactory = m::mock(ConfigurationFactory::class);
		$this->configurationFactory->shouldReceive('create')
		                           ->andReturn($this->ODMConfiguration);

		$this->connectionManager = m::mock(ConnectionManager::class);
		$this->connectionManager->shouldReceive('driver')
		                        ->andReturn(m::mock(Connection::class));
	}

	public function mockMetadata()
	{
		$this->mappingDriver = m::mock(MappingDriver::class);
		$this->mappingDriver->shouldReceive('addPaths')->with($this->settings['paths']);

		$this->metadataManager = m::mock(MetaDataManager::class);
		$this->metadataManager->shouldReceive('driver')
		                      ->once()
		                      ->andReturn($this->mappingDriver);
	}

	public function mockCacheManager()
	{
		$this->cacheManager = m::mock(CacheManager::class);
	}

	public function mockListenerRegistryListeners()
	{
		$this->listenerRegistry = m::mock(ListenerRegistry::class);
		$this->listenerRegistry->shouldReceive('getListener')
		                       ->once()
		                       ->andReturn(m::mock(OdmListenerStub::class));
	}

	public function mockListenerRegistrySubscribers()
	{
		$this->listenerRegistry = m::mock(ListenerRegistry::class);
		$this->listenerRegistry->shouldReceive('getSubscriber')
		                       ->once()
		                       ->andReturn(m::mock(OdmSubscriberStub::class));
	}

	public function setUpFactory()
	{
		$this->factory = new DocumentManagerFactory(
			$this->configurationFactory,
			$this->connectionManager,
			$this->metadataManager,
			$this->cacheManager,
			$this->listenerRegistry
		);
	}

	public function updateConfig()
	{
		$this->config = new Config($this->settings, $this->databaseConnectionsSettings);
	}

	public function tearDown()
	{
		m::close();
	}
}

class OdmFilterStub {

}

class OdmListenerStub {

}

class OdmSubscriberStub {

}

class OdmCustomType extends Type {
}