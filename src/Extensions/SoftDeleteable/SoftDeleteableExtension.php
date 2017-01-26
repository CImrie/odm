<?php


namespace CImrie\ODM\Extensions\SoftDeleteable;


use CImrie\ODM\Extensions\Extension;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\SoftDeleteable\Filter\ODM\SoftDeleteableFilter;
use Gedmo\SoftDeleteable\SoftDeleteableListener;

class SoftDeleteableExtension implements Extension
{
    public function register(DocumentManager $manager)
    {
        $subscriber = new SoftDeleteableListener();

        $manager->getEventManager()->addEventSubscriber($subscriber);

        $manager->getConfiguration()->addFilter('soft-deletes', SoftDeleteableFilter::class);
        $manager->getFilterCollection()->enable('soft-deletes');
    }

}