<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AutoGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Auto extends AbstractGenerator
{
    /**
     * @var int
     */
    protected $generatorType = ClassMetadata::GENERATOR_TYPE_AUTO;

    public function __construct()
    {
        $this->generator = new AutoGenerator();
    }
}