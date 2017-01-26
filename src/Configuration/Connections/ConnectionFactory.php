<?php


namespace CImrie\ODM\Configuration\Connections;


interface ConnectionFactory
{
    public function build(array $settings = []);
}