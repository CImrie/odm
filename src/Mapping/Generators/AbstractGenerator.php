<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AbstractIdGenerator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

abstract class AbstractGenerator implements Generator
{
    /**
     * @var AbstractIdGenerator
     */
    protected $generator;

    /**
     * @var int
     */
    protected $generatorType;

    /**
     * @var array
     */
    protected $options = [];

    public function setOptions(array $options = [])
    {
        $this->options = $options;
        $this->options['class'] = get_class($this);

        return $this;
    }

    public function commit(ClassMetadata $classMetadata)
    {
        $classMetadata->setIdGeneratorType($this->generatorType);
        $classMetadata->setIdGenerator($this->generator);
        $classMetadata->setIdGeneratorOptions($this->options);

        return $this;
    }
}