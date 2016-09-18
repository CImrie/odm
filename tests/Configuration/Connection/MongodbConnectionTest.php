<?php


use Doctrine\MongoDB\Connection;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ODM\Configuration\Connections\MongodbConnection;
use Mockery as m;

class MongodbConnectionTest extends \PHPUnit_Framework_TestCase {

	public function test_can_resolve_from_config_array()
	{
		$config = m::mock(Repository::class);
		$connection = new MongodbConnection($config);
		$resolved = $connection->resolve(
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
}