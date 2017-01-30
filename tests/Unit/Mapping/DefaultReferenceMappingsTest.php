<?php


namespace CImrie\Odm\Tests\Unit\Mapping;


use CImrie\ODM\Mapping\Reference;
use CImrie\ODM\Mapping\References\DefaultReferenceMappings;
use CImrie\ODM\Mapping\References\One;
use CImrie\ODM\Mapping\References\Reference as ReferenceInterface;
use Tests\TestCase;

class DefaultReferenceMappingsTest extends TestCase
{
    /**
     * @var ReferenceInterface | DefaultReferenceMappings
     */
    protected $reference;

    protected function setUp()
    {
        parent::setUp();
        $this->reference = new One();
    }

    /**
     * @test
     */
    public function can_store_as_db_ref_with_name()
    {
        $this->reference->storeAsDbRefWithDbName();

        $this->assertEquals(Reference::DB_REF_WITH_DB_NAME, $this->reference->asArray()['storeAs']);
    }

    /**
     * @test
     */
    public function can_store_as_db_ref_without_name()
    {
        $this->reference->storeAsDbRefWithoutDbName();

        $this->assertEquals(Reference::DB_REF_WITHOUT_DB_NAME, $this->reference->asArray()['storeAs']);
    }

    /**
     * @test
     */
    public function can_store_as_id_only()
    {
        $this->reference->storeAsId();

        $this->assertEquals(Reference::DB_REF_ID_ONLY, $this->reference->asArray()['storeAs']);
    }

    /**
     * @test
     */
    public function can_remove_orphans()
    {
        $this->reference->removeOrphans();

        $this->assertTrue($this->reference->asArray()['orphanRemoval']);
    }
    
    /**
     * @test
     */
    public function can_cascade()
    {
        $this->reference->cascade('persist');

        $this->assertEquals(['persist'], $this->reference->asArray()['cascade']);
    }
}