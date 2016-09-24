<?php


namespace LaravelDoctrine\ODM\Laravel\Console;


class GenerateDocumentsCommand extends ODMCommand {

	protected $signature = 'odm:generate:hydrators
    {--filter=* : A string pattern used to match documents that should be processed}
    {--dm= : Generate hydrators for a specific document manager. }
    {dest-path : The path to generate your hydrator classes. If none is provided, it will attempt to grab from configuration.}
    ';

	protected $description = 'Generates hydrator classes for document classes.';

	public function handle()
	{
		$this->fire(\Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateHydratorsCommand::class);
	}
}