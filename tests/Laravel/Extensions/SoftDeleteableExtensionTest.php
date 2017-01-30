<?php


namespace CImrie\Odm\Tests\Laravel\Extensions;


use CImrie\ODM\Extensions\SoftDeleteable\SoftDeleteableExtension;
use CImrie\ODM\Testing\Traits\DatabaseResets;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableDocument;
use CImrie\Odm\Tests\Traits\Odm as OdmTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Odm;
use Gedmo\Mapping\Annotation as Gedmo;
use Tests\TestCase;

class SoftDeleteableExtensionTest extends TestCase
{
    use OdmTrait, DatabaseResets;

    protected function setUp()
    {
        parent::setUp();
        $this->addMongoDbToConfig();
        $this->setExtensions([SoftDeleteableExtension::class]);
        $this->load();

        $this->resetCollection(SoftDeleteableEntity::class);
    }

    public function test_it_hides_soft_deleted_entities()
    {
        $entity = new SoftDeleteableEntity();

        $this->assertFalse($entity->isDeleted());

        $this->dm->persist($entity);
        $this->dm->flush();

        $this->assertNotNull($entity->id);
        $this->assertCount(1, $this->findAll());

        $this->dm->remove($entity);
        $this->dm->flush();

        $this->assertCount(0, $this->findAll());
        $this->assertNotNull($entity->getDeletedAt());
        $this->assertTrue($entity->isDeleted());

        $this->dm->getFilterCollection()->disable('soft-deletes');

        $this->assertCount(1, $this->findAll());
    }

    private function findAll()
    {
        return $this->dm->getRepository(SoftDeleteableEntity::class)->findAll();
    }
}

/**
 * Class SoftDeleteableEntity
 * @package Tests\Laravel\Extensions
 * @Odm\Document
 * @Gedmo\SoftDeleteable()
 */
class SoftDeleteableEntity {

    use SoftDeleteableDocument;

    /**
     * @Odm\Id()
     */
    public $id;
}