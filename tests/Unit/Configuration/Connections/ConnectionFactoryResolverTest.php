<?php


namespace Tests\Unit\Configuration\Connections;


use CImrie\ODM\Configuration\Connections\ConnectionResolver;
use CImrie\ODM\Configuration\Connections\MongodbConnectionFactory;
use Doctrine\MongoDB\Connection;

class ConnectionFactoryResolverTest extends \PHPUnit_Framework_TestCase
{
    public function test_can_resolve_a_connection_factory()
    {
        $databaseConnectionSettings = [
            'mongodb' => [
                'driver'   => 'mongodb',
                'host'     => 'localhost',
                'port'     => 27017,
                'database' => 'dbname',
                'username' => 'user',
                'password' => 'pass',
                'options'  => [
                    'database' => 'admin',
                ],
            ],
        ];

        $factories = [
            'mongodb' => new MongodbConnectionFactory()
        ];

        $manager = new ConnectionResolver($factories, $databaseConnectionSettings);

        $this->assertInstanceOf(Connection::class, $manager->resolve('mongodb'));
    }
}