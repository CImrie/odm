<?php


namespace CImrie\ODM\Configuration\MetaData;


use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

class Annotations extends AbstractMetadata {

	public function resolve(array $settings = [])
	{
		$driver = (AnnotationDriver::create(array_get($settings, 'paths', [])));
		AnnotationDriver::registerAnnotationClasses();

		$specificClasses = array_get($settings, 'documents', []);
        foreach($specificClasses as $specificClass)
        {
            $driver->loadMetadataForClass($specificClass, new ClassMetadata($specificClass));
        }

		return $driver;
	}
}