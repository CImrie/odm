<?php


namespace LaravelDoctrine\ODM\Laravel\Console;


use Doctrine\ODM\MongoDB\Tools\Console\Command\ClearCache\MetadataCommand;

class ClearMetadataCommand extends ODMCommand {

	protected $signature = 'odm:clear-cache:metadata
	{--dm= : Clear cache for a specific document manager }
	';

	protected $description = 'Clear all metadata cache of the various cache drivers';

	public function handle()
	{
		$this->fire(MetadataCommand::class);
	}
}