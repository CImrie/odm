<?php


namespace LaravelDoctrine\ODM\Mapping\References;


class Many implements Reference {

    use DefaultReferenceMappings,
        ComplexReferenceMappings
    ;

    public function discriminateUsing(array $map)
    {
        $this->mapping['discriminatorMap'] = $map;

        return $this;
    }

    public function asArray()
    {
        return $this->mapping;
    }
}