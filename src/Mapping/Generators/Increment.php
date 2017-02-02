<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\IncrementGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Increment extends AbstractGenerator
{
    public function __construct()
    {
        $this->generator = new IncrementGenerator();
    }

    public function commit(ClassMetadata $classMetadata)
    {
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_INCREMENT);
        $classMetadata->setIdGenerator($this->generator);
    }

}