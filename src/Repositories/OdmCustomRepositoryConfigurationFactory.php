<?php


namespace CImrie\ODM\Repositories;


use CImrie\ODM\Common\ConfigurationFactory;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\Repository\RepositoryFactory;

class OdmCustomRepositoryConfigurationFactory implements ConfigurationFactory
{
    /**
     * @var RepositoryFactory
     */
    protected $repositoryFactory;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->repositoryFactory = $repositoryFactory;
    }

    public function create()
    {
        $config = new Configuration();
        $config->setRepositoryFactory($this->repositoryFactory);

        return $config;
    }

}