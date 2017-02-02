<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AbstractIdGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class Custom implements Generator
{
    /**
     * @var AbstractIdGenerator
     */
    protected $generator;
    protected $options = [];

    public function __construct(AbstractIdGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    public function commit(ClassMetadata $classMetadata)
    {
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_CUSTOM);
        $classMetadata->setIdGeneratorOptions($this->options);
        $classMetadata->setIdGenerator($this->generator);

        return $this;
    }


}