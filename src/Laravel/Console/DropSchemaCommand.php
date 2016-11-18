<?php


namespace CImrie\ODM\Laravel\Console;


use Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\DropCommand;

class DropSchemaCommand extends ODMCommand {

	protected $signature = 'odm:schema:drop
	{--dm= : Drop schema for specific document manager}
    {--class= : Document class to process (default: all classes)}
    {--timeout= : Timeout (ms) for acknowledged index creation. }
    {--'.DropCommand::DB.' : Drop databases. }
    {--'.DropCommand::COLLECTION.' : Drop collections. }
    {--'.DropCommand::INDEX.' : Drop indexes. }
    '
	;

	protected $description = 'Drop databases, collections and indexes for your documents';

	public function handle()
	{
		$this->fire(DropCommand::class);
	}
}