<?php


namespace CImrie\ODM\Laravel\Console;


class GenerateRepositoriesCommand extends OdmCommand {

	protected $signature = 'odm:generate:repositories
    {--filter=* : A string pattern used to match documents that should be processed}
    {--dm= : Generate repositories for a specific document manager. }
    {dest-path : The path to generate your repository classes. If none is provided, it will attempt to grab from configuration.}
    ';

	protected $description = 'Generate repository classes from your mapping information.';

	public function handle()
	{
		$this->fire(\Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateRepositoriesCommand::class);
	}
}