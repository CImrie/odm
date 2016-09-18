<?php


namespace LaravelDoctrine\ODM\Configuration\MetaData;


use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaData;

class Annotations extends MetaData {

	public function resolve(array $settings = [])
	{
		$driver =(AnnotationDriver::create(array_get($settings, 'paths', [])));
		AnnotationDriver::registerAnnotationClasses();

		return $driver;
	}

	/**
	 * @return string
	 */
	public function getClassMetadataFactoryName()
	{
		return ClassMetadataFactory::class;
	}

}