<?php


namespace CImrie\ODM;


use CImrie\ODM\Common\ConfigurationFactory;
use CImrie\ODM\Laravel\Traits\OdmConfig;
use CImrie\ODM\Repositories\OdmCustomRepositoryConfigurationFactory;
use CImrie\ODM\Repositories\RepositoryMappingProvider;
use CImrie\ODM\Repositories\RepositoryResolverRegistry;
use CImrie\ODM\Repositories\ResolverCallbacks;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\RepositoryFactory;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class OdmRepositoryServiceProvider extends ServiceProvider
{
    use OdmConfig, ResolverCallbacks;

    public $repositories = [];

    protected $defer = true;

    public function provides()
    {
        return [
            RepositoryResolverRegistry::class,
            ConfigurationFactory::class,
            RepositoryFactory::class
        ];
    }

    public function register()
    {
        /*
         * Allow configuration via config file, or via a RepositoryMappingProvider instance.
         */
        if($repos = $this->getConfig('repositories.map'))
        {
            $this->repositories = $repos + $this->repositories;
        }

        if($provider = $this->getConfig('repositories.provider'))
        {
            /** @var RepositoryMappingProvider $provider */
            $provider = $this->app->make($provider);
            $this->repositories = $provider->getRepositories() + $this->repositories;
        }

        $this->app->singleton(RepositoryResolverRegistry::class);
        $this->app->bind(ConfigurationFactory::class, OdmCustomRepositoryConfigurationFactory::class);

        /*
         * Add the custom repositories and their resolvers to the registry after boot.
         */
        $this->app->afterResolving(RepositoryResolverRegistry::class, function(RepositoryResolverRegistry $resolverRegistry){
                foreach($this->repositories as $document => $repository)
                {
                    $resolverRegistry->addResolver($document, $this->getResolverFor($repository));
                }
        });

        /*
         * Bind repository class abstracts to the real implementations,
         * which are only ever resolved by the RepositoryResolverRegistry.
         */
        foreach($this->repositories as $document => $repository)
        {
            $this->app->singleton($repository, function(Container $app) use($document) {
                /** @var RepositoryFactory $factory */
                $factory = $app->make(RepositoryFactory::class);
                return $factory->getRepository($app->make(DocumentManager::class), $document);
            });
        }

        $this->app->bind(RepositoryFactory::class, RepositoryResolverRegistry::class);
    }
}