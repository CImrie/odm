<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\UuidGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Uuid extends AbstractGenerator
{
    protected $generatorType = ClassMetadata::GENERATOR_TYPE_UUID;

    public function __construct()
    {
        $this->generator = new UuidGenerator();
    }
}