<?php


namespace CImrie\ODM\Mapping;

/**
 * Convenience builder for an array of indexes
 * @package CImrie\ODM\Mapping
 */
class Indexes implements Builder
{
    protected $mapping = [];

    public function add(Index $index)
    {
        $this->mapping[] = $index->asArray();

        return $this;
    }

    public function asArray()
    {
        return $this->mapping;
    }
}