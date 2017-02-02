<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AlnumGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class AlphaNumeric extends AbstractGenerator
{
    /**
     * @var int
     */
    protected $generatorType = ClassMetadata::GENERATOR_TYPE_ALNUM;

    public function __construct()
    {
        $this->generator = new AlnumGenerator();
    }
}