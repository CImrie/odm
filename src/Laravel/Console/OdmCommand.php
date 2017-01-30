<?php


namespace CImrie\ODM\Laravel\Console;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\HelperSet;

abstract class OdmCommand extends Command {

	/**
	 * @var ManagerRegistry
	 */
	protected $managerRegistry;

	/**
	 * @var DocumentManager
	 */
	protected $documentManager;

	/**
	 * ODMCommand constructor.
	 * Sets up to use the command with the default document manager
	 *
	 * @param ManagerRegistry $managerRegistry
	 */
	public function __construct(ManagerRegistry $managerRegistry)
	{
		parent::__construct();
		$this->managerRegistry = $managerRegistry;
	}

	public function useSpecificDocumentManager($name)
	{
		$this->documentManager = $this->managerRegistry->getManager($name);
	}

	public function fire($commandClass)
	{
		if($this->hasOption('dm'))
		{
			$managers = [$this->option('dm')]; //use one specific manager
		} else {
			$managers = $this->managerRegistry->getManagerNames(); //otherwise apply to all
		}

		foreach($managers as $manager)
		{
			$this->useSpecificDocumentManager($manager);

			$this->getHelperSet()->set(new DocumentManagerHelper($this->documentManager));
			/**
			 * @var \Symfony\Component\Console\Command\Command $command
			 */
			$command = new $commandClass;
			$command->setApplication($this->getApplication());
			$command->execute($this->input, $this->output);
		}

	}
}