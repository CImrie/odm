<?php


namespace CImrie\ODM\Mapping\References;


use CImrie\ODM\Mapping\Traits\Collection;
use CImrie\ODM\Mapping\Traits\DiscriminatorMap;

class Many implements Reference {

    use DefaultReferenceMappings,
        ComplexReferenceMappings,
        DiscriminatorMap,
        Collection
    ;

    public function asArray()
    {
        return $this->mapping;
    }

    public function isMany()
    {
        return true;
    }

    public function isOne()
    {
        return false;
    }
}