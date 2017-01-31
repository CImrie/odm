<?php


namespace CImrie\ODM\Repositories;


use CImrie\ODM\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DefaultRepositoryFactory;
use Doctrine\ODM\MongoDB\Repository\RepositoryFactory;

class RepositoryResolverRegistry implements RepositoryFactory
{
    /**
     * @var RepositoryResolver[]
     */
    protected $resolvers = [];

    public function addResolver($document, \Closure $closure)
    {
        $this->resolvers[$document] = new RepositoryResolver($document, $closure);
    }

    public function getResolver($document)
    {
        if(!isset($this->resolvers[$document]))
        {
            return null;
        }

        return $this->resolvers[$document];
    }

    public function getRepository(DocumentManager $documentManager, $documentName)
    {
        if(!isset($this->resolvers[$documentName]))
        {
            return (new DefaultRepositoryFactory())->getRepository($documentManager, $documentName);
        }

        return $this->resolvers[$documentName]->getRepository($documentManager);
    }
}