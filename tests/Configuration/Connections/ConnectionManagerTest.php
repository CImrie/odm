<?php

namespace Tests\Configuration\Connections;

use Illuminate\Container\Container;
use LaravelDoctrine\ODM\Configuration\Connections\ConnectionManager;
use Mockery as m;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase  {
	public function test_resolves_to_odm_connections_namespace()
	{
		$connection = new ConnectionManager(m::mock(Container::class));
		$this->assertEquals(\LaravelDoctrine\ODM\Configuration\Connections::class, $connection->getNamespace());
	}
}