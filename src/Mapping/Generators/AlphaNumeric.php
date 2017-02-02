<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AlnumGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class AlphaNumeric extends AbstractGenerator
{
    public function __construct()
    {
        $this->generator = new AlnumGenerator();
    }

    public function commit(ClassMetadata $classMetadata)
    {
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_ALNUM);
        $classMetadata->setIdGenerator($this->generator);
    }

}