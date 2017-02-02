<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Id\AbstractIdGenerator;

abstract class AbstractGenerator implements Generator
{
    /**
     * @var AbstractIdGenerator
     */
    protected $generator;

    public function setOptions(array $options = [])
    {
        foreach($options as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            $this->generator->$method($value);
        }
    }
}