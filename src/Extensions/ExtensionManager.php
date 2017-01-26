<?php


namespace CImrie\ODM\Extensions;


use CImrie\ODM\IlluminateRegistry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Gedmo\DoctrineExtensions;

class ExtensionManager
{
    protected $extensions = [];
    /**
     * @var ManagerRegistry | IlluminateRegistry
     */
    protected $managerRegistry;

    /**
     * DocumentManager names
     *
     * @var array
     */
    protected $registeredIntoChain = [];

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function boot(array $extensions)
    {
        foreach ($extensions as $extension)
        {
            $this->register($extension);
        }
    }

    public function register(Extension $extension)
    {
        foreach($this->managerRegistry->getManagers() as $name => $manager)
        {
            /** @var EntityManager $manager */
            if(!array_get($this->registeredIntoChain, $name))
            {
                DoctrineExtensions::registerMappingIntoDriverChainMongodbODM($manager->getConfiguration()->getMetadataDriverImpl());
                $this->registeredIntoChain[$name] = $manager;
            }

            $extension->register($manager);
        }
    }
}