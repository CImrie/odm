<?php


namespace CImrie\ODM\Mapping\Traits;


trait Collection
{
    use StorageStrategies;

    public function useCollectionClass($class)
    {
        $this->mapping['collectionClass'] = $class;

        return $this;
    }
}