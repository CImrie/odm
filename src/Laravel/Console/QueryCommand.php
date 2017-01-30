<?php


namespace CImrie\ODM\Laravel\Console;


class QueryCommand extends OdmCommand {

	protected $signature = 'odm:query 
	{class : The class of the entity to be queried.}
	{query : The DQL query to be executed}
	{--hydrate=true : Whether the result should be a hydrated entity}
	{--skip=0 : How many records on the DB cursor to skip by (offset)}
	{--limit=100 : Maximum number of records to retrieve}
	{--depth=7 : The dumping depth of the document graph}';

	protected $description;

	public function handle()
	{
		$this->input->setOption('hydrate', (bool) $this->option('hydrate'));
		$this->fire(\Doctrine\ODM\MongoDB\Tools\Console\Command\QueryCommand::class);
	}
}