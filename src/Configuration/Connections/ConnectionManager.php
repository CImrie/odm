<?php


namespace CImrie\ODM\Configuration\Connections;


class ConnectionManager extends \LaravelDoctrine\ORM\Configuration\Connections\ConnectionManager {

	public function getNamespace()
	{
		return __NAMESPACE__;
	}
}