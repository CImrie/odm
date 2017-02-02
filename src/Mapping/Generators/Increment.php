<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\IncrementGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Increment extends AbstractGenerator
{
    /**
     * @var int
     */
    protected $generatorType = ClassMetadata::GENERATOR_TYPE_INCREMENT;

    public function __construct()
    {
        $this->generator = new IncrementGenerator();
    }
}