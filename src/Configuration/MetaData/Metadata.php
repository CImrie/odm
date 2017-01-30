<?php


namespace CImrie\ODM\Configuration\MetaData;


use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;

interface Metadata
{
    /**
     * @param array $settings
     * @return MappingDriver
     */
    public function resolve(array $settings);

    /**
     * @return string
     */
    public function getClassMetadataFactoryName();
}