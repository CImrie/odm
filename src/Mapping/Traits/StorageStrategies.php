<?php


namespace CImrie\ODM\Mapping\Traits;


trait StorageStrategies
{
    public function useAddToSetStorageStrategy()
    {
        $this->useStorageStrategy('addToSet');

        return $this;
    }

    public function usePushAllStorageStrategy()
    {
        $this->useStorageStrategy('pushAll');

        return $this;
    }

    public function useSetStorageStrategy()
    {
        $this->useStorageStrategy('set');

        return $this;
    }

    public function useSetArrayStorageStrategy()
    {
        $this->useStorageStrategy('setArray');

        return $this;
    }

    public function useAtomicSetStorageStrategy()
    {
        $this->useStorageStrategy('atomicSet');

        return $this;
    }

    public function useAtomicSetArrayStorageStrategy()
    {
        $this->useStorageStrategy('atomicSetArray');

        return $this;
    }

    protected function useStorageStrategy($strategy)
    {
        $this->mapping['strategy'] = $strategy;

        return $this;
    }
}