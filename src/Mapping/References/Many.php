<?php


namespace CImrie\ODM\Mapping\References;


use CImrie\ODM\Mapping\Traits\Collection;
use CImrie\ODM\Mapping\Traits\DiscriminatorMap;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

class Many implements Reference {

    use DefaultReferenceMappings,
        ComplexReferenceMappings,
        DiscriminatorMap,
        Collection
    ;

    public function isMany()
    {
        return true;
    }

    public function isOne()
    {
        return false;
    }

    public function commit(ClassMetadataInfo $classMetadata)
    {
        return $classMetadata->mapOneReference($this->asArray());
    }

}