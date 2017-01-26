<?php


namespace Tests\Unit\Extensions;


use CImrie\ODM\Extensions\ExtensionManager;
use CImrie\ODM\Extensions\Timestampable\TimestampableExtension;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\Timestampable\TimestampableListener;
use Mockery as m;

class ExtensionManagerTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_registers_an_extension()
    {
        $managerRegistry = m::mock(ManagerRegistry::class);
        $config = new \Doctrine\ODM\MongoDB\Configuration();
        $config->setHydratorDir(__DIR__);
        $config->setHydratorNamespace('ns');

        $config->setProxyDir(__DIR__);
        $config->setProxyNamespace('nsproxy');

        $config->setMetadataDriverImpl(new MappingDriverChain());

        $eventManager = new EventManager();
        $defaultManager = DocumentManager::create(null, $config, $eventManager);

        $managerRegistry->shouldReceive('getManagers')->andReturn(
            [$defaultManager]
        );

        $extensionManager = new ExtensionManager($managerRegistry);
        $extensionManager->register(new TimestampableExtension());

        $this->assertInstanceOf(TimestampableListener::class, array_first($eventManager->getListeners("prePersist")));
    }
}