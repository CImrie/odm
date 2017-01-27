<?php


namespace CImrie\ODM\Mapping\References;


use CImrie\ODM\Mapping\Traits\DiscriminatorMap;

class One implements Reference
{
    use DefaultReferenceMappings,
        ComplexReferenceMappings,
        DiscriminatorMap
    ;

    public function asArray()
    {
        return $this->mapping;
    }

    public function isMany()
    {
        return false;
    }

    public function isOne()
    {
        return true;
    }
}