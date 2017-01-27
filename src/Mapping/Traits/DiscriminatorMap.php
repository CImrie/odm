<?php


namespace CImrie\ODM\Mapping\Traits;


trait DiscriminatorMap
{
    public function discriminateOn($fieldName, $defaultName = null)
    {
        $this->mapping['discriminatorField'] = $fieldName;

        if($defaultName)
        {
            $this->mapping['defaultDiscriminatorValue'] = $defaultName;
        }

        return $this;
    }

    public function discriminateUsing(array $map)
    {
        $this->mapping['discriminatorMap'] = $map;

        return $this;
    }
}