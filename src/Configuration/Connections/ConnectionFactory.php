<?php


namespace CImrie\ODM\Configuration\Connections;



use Doctrine\MongoDB\Connection;

interface ConnectionFactory
{
    /**
     * @param array $settings
     * @return Connection
     */
    public function build(array $settings = []);
}