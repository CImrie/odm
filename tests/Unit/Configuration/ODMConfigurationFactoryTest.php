<?php

namespace Tests\Unit\Configuration;

use Doctrine\ODM\MongoDB\Configuration;
use CImrie\ODM\Configuration\OdmConfigurationFactory;

class ODMConfigurationFactoryTest extends \PHPUnit_Framework_TestCase  {

	public function test_can_create_odm_configuration()
	{
		$factory = new OdmConfigurationFactory();
		$this->assertInstanceOf(Configuration::class, $factory->create());
	}
}