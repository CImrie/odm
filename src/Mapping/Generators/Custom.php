<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AbstractIdGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Custom extends AbstractGenerator
{
    /**
     * @var int
     */
    protected $generatorType = ClassMetadata::GENERATOR_TYPE_CUSTOM;

    public function __construct(AbstractIdGenerator $generator)
    {
        $this->generator = $generator;
    }
}