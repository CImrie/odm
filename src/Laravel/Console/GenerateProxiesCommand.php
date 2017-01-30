<?php


namespace CImrie\ODM\Laravel\Console;


class GenerateProxiesCommand extends OdmCommand {

	protected $signature = 'odm:generate:proxies
    {--filter=* : A string pattern used to match documents that should be processed}
    {--dm= : Generate proxies for a specific document manager. }
    {dest-path : The path to generate your proxy classes. If none is provided, it will attempt to grab from configuration.}
    ';

	protected $description = 'Generates proxy classes for document classes.';

	public function handle()
	{
		$this->fire(\Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateProxiesCommand::class);
	}
}