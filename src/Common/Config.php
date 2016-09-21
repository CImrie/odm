<?php


namespace LaravelDoctrine\ODM\Common;


class Config {

	/**
	 * @var array
	 */
	protected $managerSettings;

	/**
	 * @var array
	 */
	protected $databaseConnections;

	/**
	 * @var array
	 */
	protected $globalSettings;

	public function __construct(array $managerSettings = [], array $databaseConnections = [], array $globalSettings = [])
	{
		$this->managerSettings = $managerSettings;
		$this->databaseConnections = $databaseConnections;
		$this->globalSettings = $globalSettings;
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

	public function getSettings()
	{
		return $this->managerSettings;
	}

	public function getDatabaseConnection($name = 'default')
	{
		return array_get($this->databaseConnections, $name);
	}

	public function getCacheDriver()
	{
		return array_get($this->globalSettings, 'cache.default');
	}
}