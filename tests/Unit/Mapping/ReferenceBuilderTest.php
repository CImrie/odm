<?php


namespace CImrie\Odm\Tests\Unit\Mapping;


use CImrie\ODM\Mapping\Reference;

class ReferenceBuilderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Reference
	 */
	protected $builder;

	public function setUp()
	{
		$this->builder = new Reference();
	}

	public function test_can_reference_one_document()
	{
//		$this->assertFluentSetter($this->builder->referenceOne(
//			$property = '',
//			$entity = '',
//		));
	}
//
//	public function test_can_reference_many_documents()
//	{
//
//	}
//
//	public function test_can_reference_many_mixed_types_of_documents()
//	{
//
//	}
//
//	public function test_can_reference_many_mixed_types_of_documents_with_discriminator_map()
//	{
//
//	}
//
//	public function test_can_reference_one_to_one()
//	{
//
//	}
//
//	public function test_can_reference_one_to_many()
//	{
//
//	}
//
//	public function test_can_reference_many_to_one()
//	{
//
//	}
//
//	public function test_can_reference_many_to_many()
//	{
//
//	}
//
//	public function test_can_customise_X_to_many_with_criteria_filter()
//	{
//
//	}
//
//	public function test_can_customise_X_to_many_with_repository_method_filter()
//	{
//
//	}

	private function assertFluentSetter($builder)
	{
		$this->assertInstanceOf(Reference::class, $builder);
	}
}