<?php


namespace CImrie\ODM\Testing\Traits;


use Doctrine\ODM\MongoDB\DocumentManager;

trait DatabaseResets
{
    /** @var  DocumentManager */
    protected $dm;

    public function resetCollection($class)
    {
        $this->dm->getDocumentCollection($class)->remove([]);
    }
}