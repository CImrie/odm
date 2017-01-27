<?php


namespace CImrie\ODM\Mapping;


use CImrie\ODM\Mapping\Embeds\Many;
use CImrie\ODM\Mapping\Embeds\One;
use CImrie\ODM\Mapping\References\Reference as ReferenceBuilder;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use CImrie\ODM\Exceptions\DiscriminatorFieldCanOnlyBeSetForSingleCollectionInheritanceException;

class ClassMetadataBuilder
{

    protected $cm;

    public function __construct(ClassMetadata $classMetadata)
    {
        $this->cm = $classMetadata;
    }

    public function setMappedSuperclass()
    {
        $this->cm->isMappedSuperclass = true;
        $this->cm->isEmbeddedDocument = false;

        return $this;
    }

    public function setEmbedded()
    {
        $this->cm->isMappedSuperclass = false;
        $this->cm->isEmbeddedDocument = true;

        return $this;
    }

    public function addEmbeddedDocument(One $embed)
    {
        $this->cm->mapOneEmbedded($embed->asArray());

        return $this;
    }

    public function addManyEmbeddedDocument(Many $embed)
    {
        $this->cm->mapManyEmbedded($embed->asArray());

        return $this;
    }

    public function setCustomRepository($class)
    {
        $this->cm->setCustomRepositoryClass($class);

        return $this;
    }

    public function setCollectionName($name)
    {
        $this->cm->setCollection($name);

        return $this;
    }

    public function addIndex(Index $index)
    {
        $this->cm->addIndex($index->getKeys(), $index->getOptions());

        return $this;
    }

    public function addUniqueConstraint($fields)
    {
        if(!is_array($fields))
        {
            $fields = [$fields];
        }

        $index = new Index();
        $index
            ->unique();

        foreach($fields as $field)
        {
            $index->key($field);
        }

        $this->addIndex($index);

        return $this;
    }

    public function setWriteConcern($writeConcern)
    {
        $this->cm->setWriteConcern($writeConcern);

        return $this;
    }

    public function enableCollectionPerClassInheritance()
    {
        $this->cm->inheritanceType = ClassMetadata::INHERITANCE_TYPE_COLLECTION_PER_CLASS;

        return $this;
    }

    public function enableSingleCollectionInheritance()
    {
        $this->cm->inheritanceType = ClassMetadata::INHERITANCE_TYPE_SINGLE_COLLECTION;

        return $this;
    }

    public function setDiscriminatorField($field)
    {
        if (!$this->cm->isInheritanceTypeSingleCollection()) {
            throw new DiscriminatorFieldCanOnlyBeSetForSingleCollectionInheritanceException($this->cm->reflClass->getName());
        }

        $this->cm->discriminatorField = $field;

        return $this;
    }

    public function addDiscriminatorMapping($alias, $class)
    {
        $this->cm->discriminatorMap[$alias] = $class;

        return $this;
    }

    public function setDefaultDiscriminatorKey($key)
    {
        $this->cm->defaultDiscriminatorValue = $key;

        return $this;
    }

    public function setExplicitChangeTracking()
    {
        $this->cm->changeTrackingPolicy = ClassMetadata::CHANGETRACKING_DEFERRED_EXPLICIT;

        return $this;
    }

    public function setImplicitChangeTracking()
    {
        $this->cm->changeTrackingPolicy = ClassMetadata::CHANGETRACKING_DEFERRED_IMPLICIT;

        return $this;
    }

    public function setNotifyChangeTracking()
    {
        $this->cm->changeTrackingPolicy = ClassMetadata::CHANGETRACKING_NOTIFY;

        return $this;
    }

    public function addLifecycleEventListener($event, $method = null)
    {
        if (!$method) {
            $method = $event;
        }

        $this->cm->addLifecycleCallback($method, $event);

        return $this;
    }

    public function addField(Builder $fieldBuilder)
    {
        $this->cm->mapField($fieldBuilder->asArray());

        return $this;
    }

    public function addReference(ReferenceBuilder $reference)
    {
        $mapping = $reference->asArray();

        if ($reference->isMany()) {
            $this->cm->mapManyReference($mapping);
        }
        if ($reference->isOne()) {
            $this->cm->mapOneReference($mapping);
        }

        return $this;
    }

    public function setShardKey(array $keys, array $options)
    {
        $this->cm->setShardKey($keys, $options);

        return $this;
    }

    public function version($shouldUseVersions = true)
    {
        $this->cm->setVersioned($shouldUseVersions);

        return $this;
    }

    public function setSlaveOkay($isOkay = true)
    {
        $this->cm->setSlaveOkay($isOkay);

        return $this;
    }

    public function getClassMetadata()
    {
        return $this->cm;
    }
}