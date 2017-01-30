<?php


namespace CImrie\ODM\Mapping\References;


use CImrie\ODM\Mapping\Reference;

trait DefaultReferenceMappings
{
    protected $mapping = [
        'reference' => true,
        'storeAs' => Reference::DB_REF_WITHOUT_DB_NAME
    ];

    public function property($propertyName)
    {
        $this->mapping['fieldName'] = $propertyName;

        return $this;
    }

    /**
     * Alias of document method.
     *
     * @param $entity
     * @return $this
     */
    public function entity($entity)
    {
        return $this->document($entity);
    }

    public function document($document)
    {
        $this->mapping['targetDocument'] = $document;

        return $this;
    }

    /**
     * Alias of document method.
     *
     * @param $targetClass
     * @return $this
     */
    public function target($targetClass)
    {
        return $this->document($targetClass);
    }

    public function storeAsDbRefWithDbName()
    {
        $this->storeAs(Reference::DB_REF_WITH_DB_NAME);

        return $this;
    }

    public function storeAsDbRefWithoutDbName()
    {
        $this->storeAs(Reference::DB_REF_WITHOUT_DB_NAME);

        return $this;
    }

    public function storeAsId()
    {
        $this->storeAs(Reference::DB_REF_ID_ONLY);

        return $this;
    }

    protected function storeAs($refStorageType)
    {
        $this->mapping['storeAs'] = $refStorageType;

        return $this;
    }

    public function mappedBy($property)
    {
        $this->mapping['mappedBy'] = $property;

        return $this;
    }

    public function inversedBy($property)
    {
        $this->mapping['inversedBy'] = $property;

        return $this;
    }

    public function removeOrphans($remove = true)
    {
        $this->mapping['orphanRemoval'] = $remove;

        return $this;
    }

    public function cascade($cascade = ['all'])
    {
        if(!is_array($cascade))
        {
            $cascade = [$cascade];
        }

        $this->mapping['cascade'] = $cascade;

        return $this;
    }

    public function repositoryMethod($method)
    {
        $this->mapping['repositoryMethod'] = $method;

        return $this;
    }
}