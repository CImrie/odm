<?php


namespace CImrie\ODM\Repositories;


interface RepositoryMappingProvider
{
    /**
     * Should return a $documentClass => $repositoryResolveClosure / $repoClassName
     *
     * @return \Closure[]
     */
    public function getRepositories();
}