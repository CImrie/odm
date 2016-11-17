<?php


namespace CImrie\ODM\Mapping;


use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use CImrie\ODM\Exceptions\DiscriminatorFieldCanOnlyBeSetForSingleCollectionInheritanceException;

class ClassMetadataBuilder {

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

	public function addEmbeddedDocument($field, $class = null, $prefix = null)
	{
		$this->cm->mapOneEmbedded(
			[
				'fieldName'    => $field,
				'class'        => $class,
				'columnPrefix' => $prefix,
			]
		);

		return $this;
	}

	public function addManyEmbeddedDocument($field, $class = null, $prefix = null)
	{
		$this->cm->mapManyEmbedded(
			[
				'fieldName'    => $field,
				'class'        => $class,
				'columnPrefix' => $prefix,
			]
		);

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

	public function addIndex($fields, array $options = [])
	{
		if( ! is_array($fields))
		{
			$fields = [$fields];
		}

		$this->cm->addIndex($fields, $options);

		return $this;
	}

	public function addUniqueConstraint($fields)
	{
		$this->addIndex($fields,
			[
				'unique' => true,
			]
		);

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
		if( ! $this->cm->isInheritanceTypeSingleCollection())
		{
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
		if( ! $method)
		{
			$method = $event;
		}

		$this->cm->addLifecycleCallback($method, $event);

		return $this;
	}

	public function addField($fieldName, $type)
	{
		$this->cm->mapField([
			'fieldName' => $fieldName,
			'type'      => $type,
		]);

		return $this;
	}

	public function getClassMetadata()
	{
		return $this->cm;
	}
}