<?php


use Illuminate\Container\Container;
use LaravelDoctrine\ODM\Configuration\MetaData\MetaDataManager;
use Mockery as m;

class MetaDataManagerTest extends PHPUnit_Framework_TestCase {

	public function test_it_resolves_drivers_from_odm_namespace() {
		$container = m::mock(Container::class);
		$manager = new MetaDataManager($container);

		$this->assertEquals(LaravelDoctrine\ODM\Configuration\MetaData::class, $manager->getNamespace());
	}

}