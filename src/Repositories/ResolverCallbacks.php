<?php


namespace CImrie\ODM\Repositories;


use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\UnitOfWork;

trait ResolverCallbacks
{
    public function getResolverFor($repository)
    {
        $resolver = $repository;
        if(is_string($repository))
        {
            $resolver = function(DocumentManager $documentManager, UnitOfWork $unitOfWork, ClassMetadata $classMetadata) use($repository) {
                return new $repository($documentManager, $unitOfWork, $classMetadata);
            };
        }

        return $resolver;
    }
}