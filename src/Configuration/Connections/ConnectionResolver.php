<?php


namespace CImrie\ODM\Configuration\Connections;


use Doctrine\MongoDB\Connection;

class ConnectionResolver {

    /**
     * @var array | ConnectionFactory[]
     */
    protected $factories;

    /**
     * @var array
     */
    protected $databaseConfigs;

    public function __construct(array $factories, array $config)
    {
        $this->factories = $factories;
        $this->databaseConfigs = $config;
    }

    /**
     * Resolve a connection by using the appropriate factory
     *
     * @param $connectionName
     * @return Connection
     */
    public function resolve($connectionName)
    {
        $config = $this->databaseConfigs[$connectionName];
        $driver = $config['driver'];

        return $this->factories[$driver]->build($config);
    }
}