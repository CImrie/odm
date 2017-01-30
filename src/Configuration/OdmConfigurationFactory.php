<?php


namespace CImrie\ODM\Configuration;


use Doctrine\ODM\MongoDB\Configuration;
use CImrie\ODM\Common\ConfigurationFactory;

class OdmConfigurationFactory implements ConfigurationFactory {

    /**
     * @return Configuration
     */
	public function create()
	{
		return new Configuration();
	}

}