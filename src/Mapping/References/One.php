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
}