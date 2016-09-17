<?php


namespace LaravelDoctrine\ODM\Common;


use Doctrine\ODM\MongoDB\Configuration;

interface ConfigurationFactory {

	/**
	 * @return Configuration
	 */
	public function create();
}