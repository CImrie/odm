<?php


namespace CImrie\ODM;


use CImrie\ODM\Common\Registries\DocumentManagerRegistry;
use CImrie\ODM\Extensions\Extension;
use CImrie\ODM\Extensions\ExtensionManager;
use CImrie\ODM\Laravel\Traits\OdmConfig;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class OdmExtensionServiceProvider extends ServiceProvider
{
    use OdmConfig;

    public function boot()
    {
        
    }

    public function register()
    {
        $enabledExtensions = $this->getConfig('extensions', []);
        $this->app->tag($enabledExtensions, Extension::class);

        /*
         * Always load annotation driver and annotations as gedmo depends on them
         */
        AnnotationDriver::registerAnnotationClasses();


        $this->app->singleton(ExtensionManager::class, function(Container $app) {
            return new ExtensionManager(
                $app->make(DocumentManagerRegistry::class)
            );
        });

        /*
         * When the DocumentManagers are all loaded into the overall registry, load the extensions that are in the config file.
         */
        $this->app->afterResolving(DocumentManagerRegistry::class, function(DocumentManagerRegistry $registry, Container $app){
            $this->app->make(ExtensionManager::class)->boot($app->tagged(Extension::class) ?: []);
        });
    }
}