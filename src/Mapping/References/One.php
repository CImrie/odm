<?php


namespace CImrie\ODM\Mapping\References;


class One implements Reference
{
    use DefaultReferenceMappings,
        ComplexReferenceMappings
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