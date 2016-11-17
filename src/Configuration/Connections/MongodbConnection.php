<?php


namespace CImrie\ODM\Configuration\Connections;


use LaravelDoctrine\ORM\Configuration\Connections\Connection;

class MongodbConnection extends Connection {

	public function resolve(array $settings = [])
	{
		return new \Doctrine\MongoDB\Connection($this->buildConnectionString($settings));
	}

	private function buildConnectionString(array $settings = [])
	{
		$connectionString = "";

		if($user = array_get($settings, 'username'))
		{
			$connectionString .= $user;
			if($password = array_get($settings, 'password'))
			{
				$connectionString .= ":$password";
			}

			$connectionString .= "@";
		}

		if($host = array_get($settings, 'host', 'localhost'))
		{
			$connectionString .= "$host";
		}
		if($port = array_get($settings, 'port', 27017))
		{
			$connectionString .= ":$port";
		}

		if($database = array_get($settings, 'database'))
		{
			$connectionString .= "/$database";
		}

		if($options = array_get($settings, 'options'))
		{
			$query = "?" . http_build_query($options);
			$connectionString .= $query;
		}

		return $connectionString;
	}

}