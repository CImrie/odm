<?php


namespace CImrie\ODM\Mapping;


class Field implements Builder
{
    protected $mapping = [];

    public function name($name)
    {
        $this->mapping['fieldName'] = $name;
        
        return $this;
    }

    public function type($type)
    {
        $this->mapping['type'] = $type;
        
        return $this;
    }

    public function nullable($isNullable = true)
    {
        $this->mapping['nullable'] = $isNullable;
        
        return $this;
    }

    public function strategy($strategy = null)
    {
        if($strategy)
        {
            $this->mapping['strategy'] = $strategy;
        }

        return $this;
    }

    public function columnName($name)
    {
        $this->mapping['name'] = $name;

        return $this;
    }

    /**
     * Adds one or more fields to the 'alsoLoadFields' mapping.
     * Does not overwrite existing set fields.
     *
     * @param $fields
     * @return $this
     */
    public function alsoLoad($fields)
    {
        if(!is_array($fields))
        {
            $fields = [$fields];
        }

        foreach($fields as $field)
        {
            $this->mapping['alsoLoadFields'][] = $field;
        }

        return $this;
    }

    public function dontSave($shouldNotSave = true)
    {
        $this->mapping['notSaved'] = $shouldNotSave;

        return $this;
    }

    public function asArray()
    {
        return $this->mapping;
    }
}