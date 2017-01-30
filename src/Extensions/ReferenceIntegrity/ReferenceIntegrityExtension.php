<?php


namespace CImrie\ODM\Extensions\ReferenceIntegrity;


use CImrie\ODM\Extensions\Extension;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\ReferenceIntegrity\ReferenceIntegrityListener;

class ReferenceIntegrityExtension implements Extension
{
    /**
     * @param DocumentManager $manager
     */
    public function register(DocumentManager $manager)
    {
        $subscriber = new ReferenceIntegrityListener();
        $manager->getEventManager()->addEventSubscriber($subscriber);
    }
}