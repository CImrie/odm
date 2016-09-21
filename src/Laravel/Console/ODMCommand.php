<?php


namespace LaravelDoctrine\ODM\Laravel\Console;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\HelperSet;

class ODMCommand extends Command
{
	/**
	 * @var DocumentManager
	 */
    protected $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
    	parent::__construct();
		$this->documentManager = $documentManager;
    }

    public function fire($commandClass)
    {
	    $this->getHelperSet()->set(new DocumentManagerHelper($this->documentManager));
	    /**
	     * @var \Symfony\Component\Console\Command\Command $command
	     */
	    $command = new $commandClass;
	    $command->setApplication($this->getApplication());
	    $command->execute($this->input, $this->output);
    }
}