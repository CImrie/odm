<?php


namespace CImrie\ODM\Configuration\MetaData;


interface Metadata
{
    public function resolve(array $settings);
    public function getClassMetadataFactoryName();
}