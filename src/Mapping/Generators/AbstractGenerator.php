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

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $this->options = array_merge($this->serializeOptions(), $options);
        $this->options['class'] = get_class($this->generator);

        return $this;
    }

    /**
     * @return array
     */
    protected function serializeOptions()
    {
        $options = [];
        $methods = get_class_methods(get_class($this->generator));
        foreach ($this->getGetters($methods) as $getter) {
            $property = $this->getterToProperty($getter);
            $value = $this->generator->{$getter}();
            $options[$property] = $value;
        }

        return $options;
    }

    /**
     * @param string $getterName
     * @return string
     */
    protected function getterToProperty($getterName)
    {
        return lcfirst(str_replace('get', '', $getterName));
    }

    /**
     * @param array $methods
     * @return array
     */
    protected function getGetters(array $methods)
    {
        return array_filter($methods, function ($method) {
            return strpos($method, 'get') === 0;
        });
    }

    public function commit(ClassMetadata $classMetadata)
    {
        $this->setOptions($this->options);
        $classMetadata->setIdGeneratorType($this->generatorType);
        $classMetadata->setIdGenerator($this->generator);
        $classMetadata->setIdGeneratorOptions($this->options);

        return $this;
    }
}