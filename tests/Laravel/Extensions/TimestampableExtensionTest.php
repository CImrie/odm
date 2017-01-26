<?php


namespace Tests\Laravel\Extensions;


use CImrie\ODM\Extensions\Timestampable\TimestampableExtension;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Tests\Traits\Odm as OdmTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Odm;

class TimestampableExtensionTest extends \TestCase
{
    use OdmTrait;

    protected function setUp()
    {
        parent::setUp();
        $this->addMongoDbToConfig();
        $this->setExtensions([TimestampableExtension::class]);
    }

    public function test_it_adds_timestamp_to_entity_on_prePersist()
    {
        $this->load();

        $entity = new TimeFoo();
        /** @var DocumentManager $dm */
        $dm = $this->app->make(DocumentManager::class);

        $dm->persist($entity);
        $dm->flush();

        $this->assertNotNull($entity->getCreatedAt());
    }
}

/**
 * Class TimeFoo
 * @package Tests\Laravel\Extensions
 * @Odm\Document()
 */
class TimeFoo {

    use TimestampableDocument;

    /**
     * @var @Odm\Id
     */
    protected  $id;
}