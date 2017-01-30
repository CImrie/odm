<?php


namespace CImrie\ODM\Laravel\Console;


use Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\ShardCommand;

class ShardSchemaCommand extends OdmCommand {

	protected $signature = 'odm:schema:shard
	{--dm= : Shard schema for specific document manager}
    {--class= : Document class to process (default: all classes)}
    '
	;

	protected $description = 'Enable sharding for selected documents';

	public function handle()
	{
		$this->fire(ShardCommand::class);
	}
}