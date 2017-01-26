<?php


namespace CImrie\ODM\Extensions;


use Doctrine\ODM\MongoDB\DocumentManager;

interface Extension
{
    public function register(DocumentManager $manager);
}