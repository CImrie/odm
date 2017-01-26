<?php

namespace Tests\Unit\Configuration\Connections;

use Doctrine\MongoDB\Connection;
use Illuminate\Contracts\Config\Repository;
use CImrie\ODM\Configuration\Connections\MongodbConnectionFactory;
use Mockery as m;

class MongodbConnectionTest extends \PHPUnit_Framework_TestCase {

	public function test_can_resolve_from_config_array()
	{
		$config = m::mock(Repository::class);
		$connection = new MongodbConnectionFactory($config);
		$resolved = $connection->build(
			[
				'driver'   => 'mongodb',
				'host'     => 'host',
				'port'     => 27017,
				'database' => 'test-db',
				'username' => 'user',
				'password' => 'pass'
			]
		);
		
		$this->assertInstanceOf(Connection::class, $resolved);
		$this->assertEquals("user:pass@host:27017/test-db", $resolved->getServer());
	}

	public function test_can_build_connection_string_with_query_options() {
		$config = m::mock(Repository::class);
		$connection = new MongodbConnectionFactory($config);
		$resolved = $connection->build(
			[
				'options' => [
					'foo' => 'bar'
				]
			]
		);

		$this->assertEquals("localhost:27017?foo=bar", $resolved->getServer());
	}
}