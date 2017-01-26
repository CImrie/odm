# cimrie/odm

This is a package to help integrate The Doctrine MongoDb ODM into any framework of your choice.
The only caveat at the moment is that by defining a cache provider, you are declaring that you use
Laravel. If you use another framework, you will need to implement your own caching solution.

## Laravel Setup

Go to `config/app.php` and add the service provider to your `'providers'` section:
`CImrie\Odm\OdmServiceProvider::class`

Publish the configuration file:
`php artisan vendor:publish --tag=odm`

You can now use the default Document Manager like so:

```php
 <?php
 
 use App\Http\Controllers\Controller;
 use Doctrine\ODM\MongoDB\DocumentManager;
 
 class IndexController extends Controller {
     
     public function index(DocumentManager $documentManager) {
         // code as normal ...
         
         $documentManager->flush(); 
         // etc.
     }
     
 }
```

## General Setup

The package is geared towards laravel, but setup for other frameworks is not impossible, and 
the package still makes it easier than configuring the ODM by hand.

To generate a Document Manager, you can do the following:

```php
<?php

use CImrie\ODM\DocumentManagerFactory;
use CImrie\ODM\Configuration\OdmConfigurationFactory;
use CImrie\ODM\Configuration\Connections\ConnectionResolver;
use CImrie\ODM\Configuration\MetaData\MetaDataRegistry;
use \CImrie\ODM\Common\Registries\ListenerRegistry;
use CImrie\ODM\Configuration\MetaData\Annotations;

$connectionFactories = [
    new \CImrie\ODM\Configuration\Connections\MongodbConnectionFactory()
];

$config = [
              'mongodb' => [
                    'driver'   => 'mongodb',
                    'host'     => 'localhost',
                    'port'     => 27017,
                    'database' => 'odm',
                    'username' => 'secretuser',
                    'password' => 'secretpass',
                    'options'  => [
                        'database' => 'admin',
                    ],
              ]
          ];

$metaDataDrivers = [
    new Annotations()
];  

$dmFactory = new DocumentManagerFactory(
      new OdmConfigurationFactory(),
      new ConnectionResolver($connectionFactories, $config),
      new MetaDataRegistry([Annotations::class]),
      null, //cache manager
      new ListenerRegistry(),
      null //logger, which can be created by extending CImrie\ODM\Logging\Logger
);

/*
 *  <!------------- WARNING -------------!>
 */
//TODO - THIS PART OF THE DOCS IS NOT COMPLETE

$dmFactory->create(new \CImrie\ODM\Common\Config(
    [
        
    ],
    [
        
    ],
    [
        'database' => [
            'connections' => [
            ]     
        ]
    ]
));
```