<?php


namespace CImrie\ODM\Configuration;


use Doctrine\ODM\MongoDB\Configuration;
use CImrie\ODM\Common\ConfigurationFactory;

class ODMConfigurationFactory implements ConfigurationFactory {

	public function create()
	{
		return new Configuration();
	}

}