<?php


use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ODM\Configuration\Connection\MongodbConnection;
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

		$this->assertEquals(
			[
				'driver' => 'mongodb',
				'host' => 'host',
				'port' => 27017,
				'dbname' => 'test-db',
				'user' => 'user',
				'password' => 'pass'
			]
			, $resolved);
	}
}