<?php


namespace CImrie\ODM\Mapping\Embeds;


use CImrie\ODM\Mapping\Builder;
use CImrie\ODM\Mapping\Traits\Collection;
use CImrie\ODM\Mapping\Traits\DiscriminatorMap;

class Many implements Builder
{
    use DiscriminatorMap,
        Collection;

    protected $mapping = [];

    public function field($name)
    {
        $this->mapping['fieldName'] = $name;

        return $this;
    }

    public function entity($targetDocument)
    {
        $this->mapping['targetDocument'] = $targetDocument;

        return $this;
    }

    public function asArray()
    {
        return $this->mapping;
    }
}