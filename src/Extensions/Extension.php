<?php


namespace CImrie\ODM\Extensions;


use Doctrine\ODM\MongoDB\DocumentManager;

interface Extension
{
    /**
     * @param DocumentManager $manager
     */
    public function register(DocumentManager $manager);
}