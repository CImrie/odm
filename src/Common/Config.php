<?php


namespace LaravelDoctrine\ODM\Common;


class Config {

	protected $managerSettings;

	protected $databaseConnections;

	public function __construct(array $managerSettings = [], array $databaseConnections = [])
	{
		$this->managerSettings = $managerSettings;
		$this->databaseConnections = $databaseConnections;
	}

	public function getConnectionName()
	{
		return $this->getSetting('connection');
	}

	public function getDriverResolvedConfig()
	{
		return $this->getDatabaseConnection($this->getConnectionName());
	}

	public function getDatabase()
	{
		return array_get($this->getDriverResolvedConfig(), 'database', 'default');
	}

	public function getSetting($setting, $defaultValue = null)
	{
		return array_get($this->managerSettings, $setting, $defaultValue);
	}

	public function getDatabaseConnection($name = 'default')
	{
		return array_get($this->databaseConnections, $name);
	}
}