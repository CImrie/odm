<?php

namespace CImrie\Odm\Tests\Unit\Configuration\MetaData;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use CImrie\ODM\Configuration\MetaData\Annotations;

class AnnotationsTest extends \PHPUnit_Framework_TestCase  {

	/**
	 * @var Annotations
	 */
	protected $driver;

	public function setUp()
	{
		$this->driver = new Annotations();
	}

	public function test_can_create_annotations_driver()
	{
		$this->assertInstanceOf(AnnotationDriver::class, $this->driver->resolve([]));
	}

	public function test_can_register_paths()
	{
		$driver = $this->driver->resolve($paths = [
			'paths' => [
				'/path/to/mappings',
				'/another/path',
			]
		]);

		$this->assertCount(2, $driver->getPaths());
		$this->assertEquals($paths['paths'], $driver->getPaths());
	}

	public function test_metadata_factory_is_odm_implementation() {
		$this->assertEquals(ClassMetadataFactory::class, $this->driver->getClassMetadataFactoryName());
	}
}