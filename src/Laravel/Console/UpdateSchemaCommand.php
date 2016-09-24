<?php


namespace LaravelDoctrine\ODM\Laravel\Console;


use Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\UpdateCommand;

class UpdateSchemaCommand extends ODMCommand {

	protected $signature = 'odm:schema:update
	{--dm= : Update schema for specific document manager}
    {--class= : Document class to process (default: all classes)}
    {--timeout= : Timeout (ms) for acknowledged index creation. }
    '
	;

	protected $description = 'Update indexes for your documents';

	public function handle()
	{
		$this->fire(UpdateCommand::class);
	}
}