<?php


namespace LaravelDoctrine\ODM\Configuration\MetaData;


use Doctrine\ODM\MongoDB\Configuration;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaData;

class Annotations extends MetaData {

	public function resolve(array $settings = [])
	{
		return (new Configuration())->newDefaultAnnotationDriver(
			array_get($settings, 'paths', [])
		);
	}

}