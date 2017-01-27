<?php


namespace CImrie\ODM\Mapping;


class Index implements Builder
{
    protected $mapping = [
        'keys'    => [],
        'options' => []
    ];

    public function key($field, $sort = 'asc')
    {
        $this->mapping['keys'][$field] = $sort;

        return $this;
    }

    public function unique($isUnique = true)
    {
        $this->addOption('unique', $isUnique);

        return $this;
    }

    public function name($name)
    {
        $this->addOption('name', $name);

        return $this;
    }

    public function partial($partialFilterExpression = '{}')
    {
        $this->mapping['partialFilterExpression'] = $partialFilterExpression;

        return $this;
    }

    public function sparse($isSparse = true)
    {
        $this->addOption('sparse', $isSparse);

        return $this;
    }

    public function expireAfterSeconds($seconds = 1440)
    {
        $this->addOption('expireAfterSeconds', $seconds);

        return $this;
    }

    public function buildInBackground($buildInBackground = true)
    {
        $this->addOption('background', $buildInBackground);

        return $this;
    }

    public function timeout($milliseconds = 3000)
    {
        $this->addOption('socketTimeoutMS', $milliseconds);

        return $this;
    }

    public function maxExecutionTime($milliseconds = 3000)
    {
        $this->addOption('maxTimeMS', $milliseconds);

        return $this;
    }

    public function getKeys()
    {
        return $this->mapping['keys'];
    }

    public function getOptions()
    {
        return $this->mapping['options'];
    }

    public function asArray()
    {
        return $this->mapping;
    }

    private function addOption($key, $value)
    {
        $this->mapping['options'][$key] = $value;

        return $this;
    }
}