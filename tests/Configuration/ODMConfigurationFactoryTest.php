<?php

namespace Tests\Configuration;

use Doctrine\ODM\MongoDB\Configuration;
use CImrie\ODM\Configuration\ODMConfigurationFactory;

class ODMConfigurationFactoryTest extends \PHPUnit_Framework_TestCase  {

	public function test_can_create_odm_configuration()
	{
		$factory = new ODMConfigurationFactory();
		$this->assertInstanceOf(Configuration::class, $factory->create());
	}
}