<?php


namespace CImrie\ODM\Configuration\MetaData;


use Illuminate\Contracts\Container\Container;

class MetaDataManager extends \LaravelDoctrine\ORM\Configuration\MetaData\MetaDataManager  {

	public function getNamespace()
	{
		return __NAMESPACE__;
	}
}