<?php


namespace CImrie\ODM\Mapping;


class Discriminator implements Builder
{
    /**
     * @var array
     */
    protected $mapping = [];

    public function field($name)
    {
        $this->mapping['discriminatorField'] = $name;

        return $this;
    }

    public function withMap(array $map)
    {
        $this->mapping['discriminatorMap'] = $map;

        return $this;
    }

    public function setDefaultValue($value)
    {
        $this->mapping['defaultDiscriminatorValue'] = $value;

        return $this;
    }

    public function addMapping($key, $class)
    {
        $this->mapping['discriminatorMap'][$key] = $class;

        return $this;
    }

    public function asArray()
    {
        return $this->mapping;
    }

}