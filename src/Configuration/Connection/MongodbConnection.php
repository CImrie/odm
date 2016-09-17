<?php


namespace LaravelDoctrine\ODM\Configuration\Connection;


use LaravelDoctrine\ORM\Configuration\Connections\Connection;

class MongodbConnection extends Connection {

	public function resolve(array $settings = [])
	{
		return [
			'driver'   => 'mongodb',
			'host'     => array_get($settings, 'host'),
			'port'     => array_get($settings, 'port', 27017),
			'dbname'   => array_get($settings, 'database'),
			'user'     => array_get($settings, 'username'),
			'password' => array_get($settings, 'password'),
		];
	}

}