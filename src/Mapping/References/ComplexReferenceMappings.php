<?php


namespace CImrie\ODM\Mapping\References;


trait ComplexReferenceMappings
{
    /**
     * Add a sort to the reference.
     * Call multiple times to chain sorts.
     *
     * @param $sortBy
     * @param $order
     * @return $this
     */
    public function sort($sortBy, $order)
    {
        $this->mapping['sort'][$sortBy] = $order;

        return $this;
    }

    public function criteria(array $criteria)
    {
        $originalCriteria = [];
        if(isset($this->mapping['criteria']))
        {
            $originalCriteria = $this->mapping['criteria'];
        }

        $this->mapping['criteria'] = array_merge($originalCriteria, $criteria);

        return $this;
    }

    public function limit($count)
    {
        $this->mapping['limit'] = $count;

        return $this;
    }

    public function skip($count)
    {
        $this->mapping['skip'] = $count;

        return $this;
    }
}