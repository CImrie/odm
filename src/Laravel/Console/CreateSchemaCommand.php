<?php


namespace LaravelDoctrine\ODM\Laravel\Console;


use Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\CreateCommand;

class CreateSchemaCommand extends ODMCommand {

	protected $signature = 'odm:schema:create
	{--dm= : Create schema for specific document manager}
    {--class= : Document class to process (default: all classes)}
    {--timeout= : Timeout (ms) for acknowledged index creation. }
    {--'.CreateCommand::DB.' : Create databases. }
    {--'.CreateCommand::COLLECTION.' : Create collections. }
    {--'.CreateCommand::INDEX.' : Create indexes. }
    '
	;

	protected $description = 'Create databases, collections and indexes for your documents';

	public function handle()
	{
		$this->fire(CreateCommand::class);
	}
}