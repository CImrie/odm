<?php


namespace CImrie\ODM\Mapping\Generators;


use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

interface Generator
{
    public function commit(ClassMetadata $classMetadata);
}