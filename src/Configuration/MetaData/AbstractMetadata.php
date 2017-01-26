<?php


namespace CImrie\ODM\Configuration\MetaData;


use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;

abstract class AbstractMetadata implements Metadata
{
    /**
     * @return string
     */
    public function getClassMetadataFactoryName()
    {
        return ClassMetadataFactory::class;
    }
}