<?php


namespace CImrie\ODM\Configuration\Connections;



use Doctrine\MongoDB\Connection;

class MongodbConnectionFactory implements ConnectionFactory {

    /**
     * @param array $settings
     * @return Connection
     */
	public function build(array $settings = [])
	{
		return new Connection($this->buildConnectionString($settings));
	}

    /**
     * @param array $settings
     * @return string
     */
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