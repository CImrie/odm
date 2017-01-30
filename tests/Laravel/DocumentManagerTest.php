<?php


namespace CImrie\Odm\Tests\Laravel;


use CImrie\ODM\Configuration\MetaData\Annotations;
use CImrie\ODM\Extensions\Timestampable\TimestampableExtension;
use CImrie\ODM\Logging\Loggable;
use CImrie\ODM\Logging\Logger;
use CImrie\ODM\OdmServiceProvider;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\Timestampable\TimestampableListener;
use Tests\TestCase;
use CImrie\Odm\Tests\Traits\Odm as OdmTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Odm;

class DocumentManagerTest extends TestCase
{
    use OdmTrait;

    /** @var  DocumentManager */
    protected $dm;

    protected function setUp()
    {
        parent::setUp();
        $this->addMongoDbToConfig();
    }

    public function test_can_load_odm_into_laravel()
    {
        $this->load();
        $this->assertInstanceOf(DocumentManager::class, $this->dm);
        $this->assertInstanceOf(Connection::class, $this->dm->getConnection());
    }

    public function test_load_extensions()
    {
        $this->setExtensions([TimestampableExtension::class]);
        $this->load();

        $eventListeners = $this->dm->getEventManager()->getListeners('prePersist');
        $this->assertInstanceOf(TimestampableListener::class, array_first($eventListeners));
    }

    /**
     * @test
     */
    public function can_load_logger()
    {
//        $this->load();

        /** @var OdmServiceProvider $provider */
        $this->app->register(OdmServiceProvider::class);
        $provider = $this->app->getProvider(OdmServiceProvider::class);
        $this->app->make('config')->set('odm.logger', ExampleLogger::class);
        $provider->register();

        $this->dm = $this->app->make(DocumentManager::class);

        $this->assertInstanceOf(\Closure::class, $this->dm->getConfiguration()->getLoggerCallable());
    }
}

/**
 * Class SpecificEntity
 * @package Tests\Laravel
 * @Odm\Document
 */
class SpecificEntity {

    /**
     * @Odm\Id
     */
    protected $id;
}

class ExampleLogger extends Logger {

    public function log(array $log)
    {
        return 'logged!';
    }

}