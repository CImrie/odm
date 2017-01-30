<?php


namespace CImrie\ODM\Laravel\Console;


class GenerateDocumentsCommand extends OdmCommand {

	protected $signature = 'odm:generate:documents
    {dest-path? : Path you want entities to be generated in }
    {--filter=* : A string pattern used to match documents that should be processed.}
    {--dm= : Generate documents for a specific document manager. }
    {--generate-annotations= : Flag to define if generator should generate annotation metadata on documents.}
    {--generate-methods= : Flag to define if generator should generate stub methods on documents.}
    {--regenerate-documents= : Flag to define if generator should regenerate document if it exists.}
    {--update-documents= : Flag to define if generator should only update document if it exists.}
    {--extend= : Defines a base class to be extended by generated document classes.}
    {--num-spaces=4 : Defines the number of indentation spaces.}
    {--no-backup= : Flag to define if generator should avoid backuping existing document file if it exists}';

	protected $description = 'Generate document classes and method stubs from your mapping information.';

	public function handle()
	{
		$this->fire(\Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateDocumentsCommand::class);
	}
}