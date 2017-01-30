<?php


namespace CImrie\ODM\Mapping\References;


use CImrie\ODM\Mapping\Builder;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

interface Reference extends Builder {

    /**
     * Check if the reference is for an x-To-Many relationship
     *
     * @return boolean
     */
	public function isMany();

    /**
     * Check if the reference is for an x-To-One relationship
     *
     * @return mixed
     */
	public function isOne();

    /**
     * Store and save the reference in the metadata.
     *
     * @param ClassMetadataInfo $classMetadata
     * @return mixed
     */
    public function commit(ClassMetadataInfo $classMetadata);
}