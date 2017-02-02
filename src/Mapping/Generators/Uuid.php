<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\UuidGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Uuid extends AbstractGenerator
{
    public function __construct()
    {
        $this->generator = new UuidGenerator();
    }

    public function commit(ClassMetadata $classMetadata)
    {
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_UUID);
        $classMetadata->setIdGenerator($this->generator);
    }

}