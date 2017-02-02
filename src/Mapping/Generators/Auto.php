<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AutoGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Auto extends AbstractGenerator
{
    public function __construct()
    {
        $this->generator = new AutoGenerator();
    }

    public function commit(ClassMetadata $classMetadata)
    {
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_AUTO);
        $classMetadata->setIdGenerator($this->generator);
    }

}