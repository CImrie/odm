<?php


namespace CImrie\ODM\Extensions\Timestampable;


use CImrie\ODM\Extensions\Extension;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\Timestampable\TimestampableListener;

class TimestampableExtension implements Extension
{
    /**
     * @param DocumentManager $manager
     */
    public function register(DocumentManager $manager)
    {
        $subscriber = new TimestampableListener();
        $manager->getEventManager()->addEventSubscriber($subscriber);
    }
}