<?php

use CImrie\ODM\Configuration\Connections\MongodbConnectionFactory;
use CImrie\ODM\Configuration\MetaData\Annotations;

return [

	/*
	|--------------------------------------------------------------------------
	| Document Mangers
	|--------------------------------------------------------------------------
	|
	| Configure your Document Manager(s). You can have more than one, but
	| keep in mind that extensions are loaded and enabled across all managers.
	|
	| Available meta driver(s): annotations
	|
	| Available connection(s): mongodb
	| (Connections can be configured in the database config)
	|
	| --> Warning: Proxy auto generation should only be enabled in dev!
	|
	*/
	'managers'                  => [
		'default' => [
			'dev'        => env('APP_DEBUG'),
			'meta'       => env('DOCTRINE_METADATA', Annotations::class),
			'connection' => env('DB_CONNECTION', 'mongodb'),
			'repository' => Doctrine\ODM\MongoDB\DocumentRepository::class,
			'proxies'    => [
				'namespace'     => 'Proxies',
				'path'          => storage_path('proxies'),
				'auto_generate' => env('DOCTRINE_PROXY_AUTOGENERATE', false)
			],
			'hydrators' => [
				'namespace' => 'Hydrators',
				'path' => storage_path('hydrators'),
				'auto_generate' => env('DOCTRINE_HYDRATOR_AUTOGENERATE', true)
			],
			/*
			|--------------------------------------------------------------------------
			| Doctrine events
			|--------------------------------------------------------------------------
			|
			| The listener array expects the key to be a Doctrine event
			| e.g. Doctrine\ORM\Events::onFlush
			|
			*/
			'events'     => [
				'listeners'   => [],
				'subscribers' => []
			],
			'filters'    => [],
			/*
			|--------------------------------------------------------------------------
			| Doctrine mapping types
			|--------------------------------------------------------------------------
			|
			| Link a Database Type to a Local Doctrine Type.
			| Due to the lack of documentation on ODM, check out the ORM references
			| for more info. They are broadly the same.
			| Also, check out the class "Doctrine\ODM\MongoDB\Types\Type" to see how
			| they get loaded in (using the AddType method).
			|
			| References:
			| http://doctrine-orm.readthedocs.org/en/latest/cookbook/custom-mapping-types.html
			| http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#custom-mapping-types
			| http://doctrine-orm.readthedocs.org/en/latest/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html
			| http://doctrine-orm.readthedocs.org/en/latest/reference/basic-mapping.html#reference-mapping-types
			| http://symfony.com/doc/current/cookbook/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool
			|--------------------------------------------------------------------------
			*/
			'mapping_types'              => [
				//'enum' => 'string'
			]
		]
	],
    /*
     |------------------------------------------------------------
     | Custom Repositories
     |------------------------------------------------------------
     |
     | Set custom repositories here.
     | You can provide a map of $documentClass => $repoClass.
     | Alternatively if you require dependency injection, you can
     | supply a 'provider' class.
     |
     | The provider class should implement:
     | CImrie\ODM\Repositories\RepositoryMappingProvider
     |
     | This provider should return an array of closures that return
     | a new instance of the repository for a yet-undetermined
     | document manager, unit of work and class metadata.
     |
     | The provider class is resolved from the container so you can
     | inject any requirements into its constructor.
     |
     */
    'repositories' => [
        'map' => [
            // Document Class => Repository Class Name
        ],
        'provider' => null
    ],
	/*
	|--------------------------------------------------------------------------
	| Doctrine Extensions
	|--------------------------------------------------------------------------
	|
	| Enable/disable Doctrine Extensions by adding or removing them from the list
	|
	| Gedmo extensions are included by default with this package as they are
	| frequently used. Set'use_extensions' to false if you wish to disable
	| all extension-related activity.
	| (i.e. prevent load of OdmExtensionServiceProvider)
	|
	*/
	'use_extensions' => true,
	'extensions'                => [

	],
	/*
	|--------------------------------------------------------------------------
	| Doctrine custom types
	|--------------------------------------------------------------------------
	|
	| Create a custom or override a Doctrine Type
	|--------------------------------------------------------------------------
	*/
	'custom_types'              => [
	    // mytype => MyType::class
	],
	/*
	|--------------------------------------------------------------------------
	| Enable query logging.
	| Set to false to disable, or a class name to use a particular logger.
	| No loggers are provided by default but can be easily done so by extending
	| CImrie\Odm\Logging\Logger and implementing the 'log' method.
	|
	| See:
	| http://docs.doctrine-project.org/projects/doctrine-mongodb-odm/en/latest/reference/logging.html
	|
	|--------------------------------------------------------------------------
	*/
	'logger'                    => env('DOCTRINE_LOGGER', false),
	/*
	|--------------------------------------------------------------------------
	| Cache
	|--------------------------------------------------------------------------
	|
	| Configure meta-data, query and result caching here.
	| Optionally you can enable second level caching.
	|
	| Available: acp|array|file|memcached|redis|void
	|
	*/
	'cache'                     => [
		'default'                => env('DOCTRINE_CACHE', 'array'),
		'namespace'              => null,
		'second_level'           => false,
	],
    /*
     |------------------------------------------------------------
     | Misc
     |------------------------------------------------------------
     |
     | Any internal implementation details will be configurable here.
     | For example, the available Metadata Drivers
     | (so that you can implement one yourself).
     |
     */
    'metadata_drivers' => [
        Annotations::class,
    ],
    'connection_factories' => [
        'mongodb' => MongodbConnectionFactory::class
    ],
    'use_custom_repositories' => true
];
