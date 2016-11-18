<?php

namespace Tests\Unit\Mapping;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use CImrie\ODM\Mapping\ClassMetadataBuilder;
use Tests\Unit\Models\TestUser;
use Tests\Unit\Repositories\TestRepository;

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
		$this->assertCount(0, $this->cm->getEmbeddedFieldsMappings());
		$this->assertFluentSetter($this->builder->addEmbeddedDocument('user'));
		$this->assertCount(1, $this->cm->getEmbeddedFieldsMappings());
	}

	public function test_can_add_many_embedded_documents()
	{
		$this->assertCount(0, $this->cm->getEmbeddedFieldsMappings());
		$this->assertFluentSetter($this->builder->addManyEmbeddedDocument('user', TestUser::class, 'prefix_'));
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
		$this->assertFluentSetter($this->builder->addIndex('field'));
		$this->assertEquals(['field'], array_get($this->cm->indexes, '0.keys'));
		$this->assertEmpty(array_get($this->cm->indexes, '0.options'));
	}

	public function test_can_add_unique_constraint_to_a_field()
	{
		$this->assertFluentSetter($this->builder->addUniqueConstraint(['uniqueField', 'uniqueField2']));
		$this->assertEquals(['uniqueField', 'uniqueField2'], array_get($this->cm->indexes, '0.keys'));
		$this->assertContains(['unique' => true], array_get($this->cm->indexes, '0.options'));
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
		$this->assertFluentSetter($this->builder->setDiscriminatorField('custom_type'));
		$this->assertEquals('custom_type', $this->cm->discriminatorField);
	}

	public function test_can_add_subclass_to_single_collection_inheritance_discriminator_map()
	{
		$this->assertFluentSetter($this->builder->addDiscriminatorMapping('article', TestUser::class));
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
	 * Most basic and important mapping - simply mapping fields to columns in DB
	 */
	public function test_can_add_a_field_to_a_document()
	{
		$this->assertFluentSetter($this->builder->addField('name', 'string'));
		$this->assertContains(['type' => 'string'], $this->cm->getFieldMapping('name'));
	}

//	/**
//	 * @todo may need to test each type of reference depending on the reference builder
//	 */
//	public function test_can_add_references_to_other_documents()
//	{
////		$this->assertFluentSetter($this->builder->)
//	}

	private function assertFluentSetter($builder)
	{
		$this->assertInstanceOf(ClassMetadataBuilder::class, $builder);
	}
}