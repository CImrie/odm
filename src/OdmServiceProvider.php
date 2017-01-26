<?php


namespace CImrie\ODM;


use CImrie\ODM\Common\Registries\DocumentManagerRegistry;
use CImrie\ODM\Configuration\Connections\ConnectionFactory;
use CImrie\ODM\Configuration\Connections\ConnectionResolver;
use CImrie\ODM\Configuration\MetaData\Metadata;
use CImrie\ODM\Configuration\MetaData\MetaDataRegistry;
use CImrie\ODM\Extensions\ExtensionManager;
use CImrie\ODM\Laravel\Traits\OdmConfig;
use CImrie\ODM\Logging\Logger;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Proxy\Autoloader;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateHydratorsCommand;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use CImrie\ODM\Common\Config;
use CImrie\ODM\Common\ConfigurationFactory;
use CImrie\ODM\Configuration\OdmConfigurationFactory;
use CImrie\ODM\Laravel\Console\ClearMetadataCommand;
use CImrie\ODM\Laravel\Console\CreateSchemaCommand;
use CImrie\ODM\Laravel\Console\DropSchemaCommand;
use CImrie\ODM\Laravel\Console\GenerateDocumentsCommand;
use CImrie\ODM\Laravel\Console\GenerateProxiesCommand;
use CImrie\ODM\Laravel\Console\GenerateRepositoriesCommand;
use CImrie\ODM\Laravel\Console\QueryCommand;
use CImrie\ODM\Laravel\Console\ShardSchemaCommand;
use CImrie\ODM\Laravel\Console\UpdateSchemaCommand;

class OdmServiceProvider extends ServiceProvider
{
    use OdmConfig;

    /**
     * Boot service provider.
     */
    public function boot()
    {
        $this->publishes([
            $this->getConfigPath() => config_path($this->getConfigName() . '.php'),
        ], 'odm');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();

        $this->registerMetadataDriverRegistry();
        $this->registerConnectionFactories();
        $this->registerQueryLogger();
        $this->registerManagerRegistry();
        $this->registerDefaultDocumentManager();
        $this->registerAutoloader();
        $this->registerConsoleCommands();
        $this->registerExtensions();
    }

    public function registerMetadataDriverRegistry()
    {
        $this->app->tag($this->getConfig('metadata_drivers'), Metadata::class);
        $this->app->singleton(MetaDataRegistry::class, function (Container $app) {
            return new MetaDataRegistry($app->tagged(Metadata::class));
        });
    }

    public function registerConnectionFactories()
    {
        $this->app->tag($this->getConfig('connection_factories'), ConnectionFactory::class);
        $this->app->singleton(ConnectionResolver::class, function (Container $app) {
            $factories = $app->tagged(ConnectionFactory::class);
            $keyedFactories = [];

            foreach ($factories as $factory) {
                $key = array_search(get_class($factory), $this->getConfig('connection_factories'));
                $keyedFactories[$key] = $factory;
            }

            return new ConnectionResolver($keyedFactories, $app->make('config')->get('database.connections'));
        });
    }

    public function registerQueryLogger()
    {
        if($logger = $this->getConfig('logger'))
        {
            $this->app->bind(Logger::class, $logger);
        }
    }

    public function registerManagerRegistry()
    {
        $this->app->bind(ConfigurationFactory::class, OdmConfigurationFactory::class);
        $this->app->singleton(DocumentManagerRegistry::class, function (Container $app) {
            $registry = new IlluminateRegistry($app, $app->make(DocumentManagerFactory::class));
            // Add all managers into the registry
            foreach ($this->getConfig('managers', []) as $manager => $managerSettings) {
                $config = new Config($managerSettings, $this->getGlobalConfig('database.connections', []), $this->getConfig());
                $registry->addManager($manager, $config);
            }

            return $registry;
        });

        $this->app->alias(DocumentManagerRegistry::class, ManagerRegistry::class);
        $this->app->alias(DocumentManagerRegistry::class, IlluminateRegistry::class);
        $this->app->alias(DocumentManagerRegistry::class, 'dm-registry');

    }

    public function registerDefaultDocumentManager()
    {
        // Bind the default Document Manager
        $this->app->singleton(DocumentManager::class, function ($app) {
            return $app->make(DocumentManagerRegistry::class)->getManager();
        });

        $this->app->alias(DocumentManager::class, 'dm');
    }

    /**
     * Register proxy and hydrator autoloader
     *
     * @return void
     */
    public function registerAutoloader()
    {
        $this->app->afterResolving(DocumentManagerRegistry::class, function (ManagerRegistry $registry) {
            /** @var DocumentManager $manager */
            foreach ($registry->getManagers() as $manager) {
                Autoloader::register(
                    $manager->getConfiguration()->getProxyDir(),
                    $manager->getConfiguration()->getProxyNamespace()
                );

            }
        });
    }

    public function registerConsoleCommands()
    {
        $this->commands([
            ClearMetadataCommand::class,
            QueryCommand::class,
            GenerateDocumentsCommand::class,
            GenerateHydratorsCommand::class,
            GenerateProxiesCommand::class,
            GenerateRepositoriesCommand::class,
            CreateSchemaCommand::class,
            DropSchemaCommand::class,
            UpdateSchemaCommand::class,
            ShardSchemaCommand::class,
        ]);
    }

    public function registerExtensions()
    {
        if ($this->getConfig('use_extensions'))
        {
            $this->app->register(OdmExtensionServiceProvider::class);
        }
    }

    /**
     * Merge config
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            $this->getConfigPath(), $this->getConfigName()
        );
    }
}