<?php


namespace LaravelDoctrine\ODM\Configuration;


use Doctrine\ODM\MongoDB\Configuration;
use LaravelDoctrine\ODM\Common\ConfigurationFactory;

class ODMConfigurationFactory implements ConfigurationFactory {

	public function create()
	{
		return new Configuration();
	}

}