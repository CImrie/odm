<?php


namespace CImrie\ODM\Repositories;


use CImrie\ODM\Common\Repository;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class RepositoryResolver
 *
 * Resolves different repositories for a document, depending on the document manager given.
 *
 * @package CImrie\ODM\Repositories
 */
class RepositoryResolver
{
    /**
     * @var string
     */
    protected $document;

    /**
     * @var \Closure
     */
    protected $factory;

    /**
     * @var Repository[$managerName]
     */
    protected $repositories = [];

    /**
     * RepositoryResolver constructor.
     * @param $document
     * @param \Closure $closure
     */
    public function __construct($document, \Closure $closure)
    {
        $this->document = $document;
        $this->factory = $closure;
    }

    public function getRepository(DocumentManager $documentManager)
    {
        $managerName = spl_object_hash($documentManager);
        if(!isset($this->repositories[$managerName]))
        {
            $factory = $this->factory;
            $this->repositories[$managerName] = $factory($documentManager, $documentManager->getUnitOfWork(), $documentManager->getClassMetadata($this->document));
        }

        return $this->repositories[$managerName];
    }

    /**
     * @return \Closure
     */
    public function getFactory()
    {
        return $this->factory;
    }
}