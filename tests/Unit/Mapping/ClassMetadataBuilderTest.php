<?php

namespace CImrie\Odm\Tests\Unit\Mapping;

use CImrie\ODM\Mapping\Embeds\Many;
use CImrie\ODM\Mapping\Embeds\One;
use CImrie\ODM\Mapping\Field;
use CImrie\ODM\Mapping\Index;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use CImrie\ODM\Mapping\ClassMetadataBuilder;
use CImrie\Odm\Tests\Models\TestUser;
use CImrie\Odm\Tests\Unit\Repositories\TestRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Odm;

/**
 * Class ClassMetadataBuilderTest
 *
 * Tests that the ClassMetadataBuilder correctly builds up metadata on a ClassMetadata object
 * for any and all mappings that can be added in the ODM.
 *
 * @package Tests\Mapping
 */
class ClassMetadataBuilderTest extends \PHPUnit_Framework_TestCase  {

	/**
	 * @var ClassMetadata
	 */
	protected $cm;

	/**
	 * @var ClassMetadataBuilder
	 */
	protected $builder;

	public function setUp()
	{
		$this->cm = new ClassMetadata(TestUser::class);
		$this->builder = new ClassMetadataBuilder($this->cm);
	}

	public function test_can_mark_as_mapped_superclass()
	{
		$this->assertFluentSetter($this->builder->setMappedSuperclass());
		$this->assertTrue($this->cm->isMappedSuperclass);
		$this->assertFalse($this->cm->isEmbeddedDocument);
	}

	public function test_can_set_embedded_document()
	{
		$this->assertFluentSetter($this->builder->setEmbedded());
		$this->assertFalse($this->cm->isMappedSuperclass);
		$this->assertTrue($this->cm->isEmbeddedDocument);
	}

	public function test_can_get_metadata_info()
	{
		$this->assertInstanceOf(ClassMetadata::class, $this->builder->getClassMetadata());
	}

	public function test_can_add_embedded_document()
	{
	    $embed = (new One)->field('user');

		$this->assertCount(0, $this->cm->getEmbeddedFieldsMappings());
		$this->assertFluentSetter($this->builder->addEmbeddedDocument($embed));
		$this->assertCount(1, $this->cm->getEmbeddedFieldsMappings());
	}

	public function test_can_add_many_embedded_documents()
	{
	    $embed = (new Many)->field('user')->entity(TestUser::class);

		$this->assertCount(0, $this->cm->getEmbeddedFieldsMappings());
		$this->assertFluentSetter($this->builder->addManyEmbeddedDocument($embed));
		$this->assertCount(1, $this->cm->getEmbeddedFieldsMappings());
	}

	public function test_can_set_custom_repository()
	{
		$this->assertFluentSetter($this->builder->setCustomRepository(TestRepository::class));
		$this->assertEquals(TestRepository::class, $this->cm->customRepositoryClassName);
	}

	public function test_can_set_collection_name()
	{
		$this->assertFluentSetter($this->builder->setCollectionName('my_custom_collection'));
		$this->assertEquals('my_custom_collection', $this->cm->collection);
	}

	public function test_can_add_index_to_a_field()
	{
	    $index = (new Index)->key('field');

		$this->assertFluentSetter($this->builder->addIndex($index));
		$this->assertEquals(['field' => 1], array_get($this->cm->indexes, '0.keys'));
		$this->assertEmpty(array_get($this->cm->indexes, '0.options'));
	}

	public function test_can_add_unique_constraint_to_a_field()
	{
		$this->assertFluentSetter($this->builder->addUniqueConstraint(['uniqueField', 'uniqueField2']));
		$this->assertEquals(['uniqueField' => 1, 'uniqueField2' => 1], array_get($this->cm->indexes, '0.keys'));
		$this->assertContains(['unique' => true], array_get($this->cm->indexes, '0.options'));
	}

	/**
	 * @test
	 */
	public function can_add_unique_constraint_to_one_field()
	{
	    $this->assertFluentSetter($this->builder->addUniqueConstraint('uniqueField'));
	    $this->assertEquals(['uniqueField' => 1], array_get($this->cm->indexes, '0.keys'));
	}

	public function test_can_set_inheritance_to_collection_per_class()
	{
		$this->assertFluentSetter($this->builder->enableCollectionPerClassInheritance());
		$this->assertTrue($this->cm->isInheritanceTypeCollectionPerClass());

		$this->assertFalse($this->cm->isInheritanceTypeSingleCollection());
		$this->assertFalse($this->cm->isInheritanceTypeNone());
	}

	public function test_can_set_inheritance_type_to_single_collection()
	{
		$this->assertFluentSetter($this->builder->enableSingleCollectionInheritance());
		$this->assertFalse($this->cm->isInheritanceTypeCollectionPerClass());
		$this->assertTrue($this->cm->isInheritanceTypeSingleCollection());
		$this->assertFalse($this->cm->isInheritanceTypeNone());
	}

	public function test_can_set_discriminator_field()
	{
		$this->assertFluentSetter($this->builder->enableSingleCollectionInheritance());
		$this->assertFluentSetter($this->builder->setDiscriminator(
            $this->builder->discriminate()->field('custom_type')
        ));

		$this->assertEquals('custom_type', $this->cm->discriminatorField);
	}

	/**
	 * @test
	 */
	public function can_set_discriminator_default_value()
	{
	    $this->assertFluentSetter($this->builder->setDiscriminator(
	        $this->builder->discriminate()->field('custom_type')->setDefaultValue('test')
        ));

	    $this->assertEquals('test', $this->cm->discriminatorValue);
	}

	public function test_can_add_subclass_to_single_collection_inheritance_discriminator_map()
	{
	    $discriminator = $this->builder->discriminate()->field('type');

		$this->assertFluentSetter(
		    $this->builder->setDiscriminator($discriminator->addMapping('article', TestUser::class))
        );

		$this->assertEquals(['article' => TestUser::class], $this->cm->discriminatorMap);
	}

	public function test_can_set_change_tracking_policy()
	{
		$this->assertFluentSetter($this->builder->setExplicitChangeTracking());
		$this->assertTrue($this->cm->isChangeTrackingDeferredExplicit());

		$this->assertFluentSetter($this->builder->setImplicitChangeTracking());
		$this->assertTrue($this->cm->isChangeTrackingDeferredImplicit());

		$this->assertFluentSetter($this->builder->setNotifyChangeTracking());
		$this->assertTrue($this->cm->isChangeTrackingNotify());
	}

	public function test_can_add_lifecycle_events()
	{
		$this->assertFluentSetter($this->builder->addLifecycleEventListener('preUpdate', 'preUpdateMethod'));
		$this->assertContains('preUpdateMethod', $this->cm->getLifecycleCallbacks('preUpdate'));
	}

	/**
	 * @test
	 */
	public function can_add_event_with_default_method()
	{
	    $this->assertFluentSetter($this->builder->addLifecycleEventListener('preUpdate'));
	    $this->assertContains('preUpdate', $this->cm->getLifecycleCallbacks('preUpdate'));
	}

	/**
	 * Most basic and important mapping - simply mapping fields to columns in DB
	 */
	public function test_can_add_a_field_to_a_document()
	{
	    $field = (new Field())
                ->name('name')
                ->type('string')
                ->nullable()
                ->columnName('customName')
            ;

		$this->assertFluentSetter($this->builder->addField($field));
		$this->assertContains(['type' => 'string', 'name' => 'customName'], $this->cm->getFieldMapping('name'));
		$this->assertEquals('customName', $this->cm->getFieldMapping('name')['name']);
		$this->assertTrue($this->cm->getFieldMapping('name')['nullable']);
	}

    public function test_can_add_also_load_to_a_field()
    {
        $field = (new Field())
                ->name('name')
                ->alsoLoad('customLoadField')
            ;

        $this->assertFluentSetter($this->builder->addField($field));
        $this->assertEquals(['customLoadField'], $this->cm->getFieldMapping('name')['alsoLoadFields']);
	}

	/**
	 * @test
	 */
	public function can_set_write_concern()
	{
	    $this->builder->setWriteConcern('{w:1}');
	    $this->assertEquals('{w:1}', $this->cm->writeConcern);
	}

	/**
	 * @test
	 */
	public function can_add_reference()
	{
	    $reference = new \CImrie\ODM\Mapping\References\One();
        $reference->document(TestUser::class)->property('user');

	    $this->builder->addReference(
	        $reference
        );

	    $this->assertTrue($this->cm->hasReference('user'));
	}

	/**
	 * @test
	 */
	public function can_version_documents()
	{
	    $this->builder->version();

	    $this->assertTrue($this->cm->isVersioned);
	}

	/**
	 * @test
	 */
	public function can_set_to_read_from_slaves()
	{
	    $this->builder->setSlaveOkay();

	    $this->assertTrue($this->cm->slaveOkay);
	}

	private function assertFluentSetter($builder)
	{
		$this->assertInstanceOf(ClassMetadataBuilder::class, $builder);
	}
}

/**
 * Class TestAlsoLoadEntity
 * @package Tests\Unit\Mapping
 * @Odm\Document
 */
class TestAlsoLoadEntity {

    /**
     * @Odm\Id
     */
    protected $id;

    /**
     * @Odm\Field('type'='string') @Odm\AlsoLoad('fullName')
     */
    protected $name;
}