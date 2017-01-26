<?php


namespace CImrie\ODM\Mapping\References;


trait ComplexReferenceMappings
{
    public function criteria(array $criteria = [])
    {
        $this->mapping['criteria'] = $criteria;

        return $this;
    }

    public function repositoryMethod($method)
    {
        $this->mapping['repositoryMethod'] = $method;

        return $this;
    }

    public function sort(array $map)
    {
        $this->mapping['sort'] = $map;

        return $this;
    }

    public function skip($offset)
    {
        $this->mapping['offset'] = $offset;

        return $this;
    }

    public function limit($limit = 1)
    {
        $this->mapping['limit'] = $limit;

        return $this;
    }
}