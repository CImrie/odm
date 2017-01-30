<?php


namespace CImrie\ODM\Mapping\References;


use CImrie\ODM\Mapping\Traits\DiscriminatorMap;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

class One implements Reference
{
    use DefaultReferenceMappings,
        ComplexReferenceMappings,
        DiscriminatorMap
    ;

    public function isMany()
    {
        return false;
    }

    public function isOne()
    {
        return true;
    }

    public function commit(ClassMetadataInfo $classMetadata)
    {
        return $classMetadata->mapManyReference($this->asArray());
    }
}