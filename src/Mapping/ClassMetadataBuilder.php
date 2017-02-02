<?php


namespace CImrie\ODM\Mapping;


use CImrie\ODM\Mapping\Embeds\Many;
use CImrie\ODM\Mapping\Embeds\One;
use CImrie\ODM\Mapping\Generators\Generator;
use CImrie\ODM\Mapping\References\Reference as ReferenceBuilder;
use CImrie\ODM\Mapping\Traits\DiscriminatorMap;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use CImrie\ODM\Exceptions\DiscriminatorFieldCanOnlyBeSetForSingleCollectionInheritanceException;

class ClassMetadataBuilder
{
    use DiscriminatorMap;

    /**
     * @var ClassMetadata
     */
    protected $cm;

    /**
     * @var array
     */
    protected $mapping = [];

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

    public function setDiscriminator(Discriminator $discriminator)
    {
        $mapping = $discriminator->asArray();
        $this->discriminator = $discriminator;

        if(isset($mapping['discriminatorField']))
        {
            $this->cm->setDiscriminatorField($mapping['discriminatorField']);
        }

        if(isset($mapping['discriminatorMap']))
        {
            $this->cm->setDiscriminatorMap($mapping['discriminatorMap']);
        }

        if(isset($mapping['defaultDiscriminatorValue']))
        {
            $this->cm->setDefaultDiscriminatorValue($mapping['defaultDiscriminatorValue']);
        }

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
        $reference->commit($this->cm);

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

    public function setIdGenerator(Generator $generator)
    {
        $generator->commit($this->cm);

        return $this;
    }

    public function getClassMetadata()
    {
        return $this->cm;
    }
}