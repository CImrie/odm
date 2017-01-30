<?php


namespace CImrie\Odm\Tests\Laravel;


use CImrie\ODM\Configuration\MetaData\Annotations;
use CImrie\ODM\Extensions\Timestampable\TimestampableExtension;
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

//    public function test_can_load_specific_documents_into_mapping_driver()
//    {
//        $this->load();
//        $config = (object) $this->app->make('config')->get('odm');
//
//        $config->managers['default']['documents'] = [SpecificEntity::class];
//        $config->metadata_drivers = [Annotations::class];
//
//        $this->app->make('config')->set('odm', (array) $config);
//
////        dd($this->dm->getClassMetadata(SpecificEntity::class));
////        dd($this->dm->getConfiguration()->getMetadataDriverImpl()->getAllClassNames());
//    }
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