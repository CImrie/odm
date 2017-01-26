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
//            'documents' => [
//
//            ],
//			'namespaces' => [
////				'App',
//			],
//			'paths'      => [
////				base_path('app')
//			],
			'repository' => Doctrine\ODM\MongoDB\DocumentRepository::class,
			'proxies'    => [
				'namespace'     => 'Proxies',
				'path'          => storage_path('proxies'),
				'auto_generate' => env('DOCTRINE_PROXY_AUTOGENERATE', false)
			],
			'hydrators' => [
				'namespace' => 'Hydrators',
				'path' => storage_path('hydrators'),
				'auto_generate' => env('DOCTRINE_HYDRATOR_AUTOGENERATE', false)
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
			| Link a Database Type to a Local Doctrine Type
			|
			| Using 'enum' => 'string' is the same of:
			| $doctrineManager->extendAll(function (\Doctrine\ORM\Configuration $configuration,
			|         \Doctrine\DBAL\Connection $connection,
			|         \Doctrine\Common\EventManager $eventManager) {
			|     $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
			| });
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
	|--------------------------------------------------------------------------
	| Doctrine Extensions
	|--------------------------------------------------------------------------
	|
	| Enable/disable Doctrine Extensions by adding or removing them from the list
	|
	| If you want to require custom extensions you will have to require
	| laravel-doctrine/extensions in your composer.json
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
		'json' => LaravelDoctrine\ORM\Types\Json::class
	],
	/*
	|--------------------------------------------------------------------------
	| DQL custom datetime functions
	|--------------------------------------------------------------------------
	*/
	'custom_datetime_functions' => [],
	/*
	|--------------------------------------------------------------------------
	| DQL custom numeric functions
	|--------------------------------------------------------------------------
	*/
	'custom_numeric_functions'  => [],
	/*
	|--------------------------------------------------------------------------
	| DQL custom string functions
	|--------------------------------------------------------------------------
	*/
	'custom_string_functions'   => [],
	/*
	|--------------------------------------------------------------------------
	| Enable query logging with laravel file logging,
	| debugbar, clockwork or an own implementation.
	| Setting it to false, will disable logging
	|
	| Available:
	| - LaravelDoctrine\ORM\Loggers\LaravelDebugbarLogger
	| - LaravelDoctrine\ORM\Loggers\ClockworkLogger
	| - LaravelDoctrine\ORM\Loggers\FileLogger
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
	|--------------------------------------------------------------------------
	| Gedmo extensions
	|--------------------------------------------------------------------------
	|
	| Settings for Gedmo extensions
	| If you want to use this you will have to require
	| laravel-doctrine/extensions in your composer.json
	|
	*/
	'gedmo'                     => [
		'all_mappings' => false
	],
    'metadata_drivers' => [
        Annotations::class,
    ],
    'connection_factories' => [
        'mongodb' => MongodbConnectionFactory::class
    ]
];
