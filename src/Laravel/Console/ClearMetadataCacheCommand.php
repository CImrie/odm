<?php


namespace LaravelDoctrine\ODM\Laravel\Console;

use Illuminate\Console\Command;
class ClearMetadataCacheCommand extends Command
{
    protected $signature = 'odm:clear-cache:metadata
    {--flush : If defined, cache entries will be flushed instead of deleted/invalidated.}
    {--dm= : Clear cache for a specific document manager }';

    protected $description = 'Clear all metadata cache of the various cache drivers';

    public function __construct()
    {

    }

    public function handle()
    {

    }
}