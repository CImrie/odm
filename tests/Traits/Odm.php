<?php


namespace Tests\Traits;


use CImrie\ODM\OdmServiceProvider;
use Doctrine\ODM\MongoDB\DocumentManager;

trait Odm
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    public function addMongoDbToConfig()
    {
        $this->app->make('config')->set('database.connections.mongodb', [
            'driver'   => 'mongodb',
            'host'     => 'localhost',
            'port'     => 27017,
            'database' => 'odm_test',
            'username' => null,
            'password' => null,
            'options'  => [
                'database' => 'admin',
            ],
        ]);
    }

    protected function load()
    {
        $this->app->register(OdmServiceProvider::class);
        $this->dm = $this->app->make(DocumentManager::class);
    }

    protected function setExtensions(array $extensions)
    {
        $this->app->make('config')->set('odm.extensions', $extensions);
    }
}