<?php


namespace CImrie\ODM\Common;


use Doctrine\ODM\MongoDB\Configuration;

interface ConfigurationFactory {

	/**
	 * @return Configuration
	 */
	public function create();
}