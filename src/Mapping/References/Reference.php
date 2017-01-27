<?php


namespace CImrie\ODM\Mapping\References;


use CImrie\ODM\Mapping\Builder;

interface Reference extends Builder {

    /**
     * Check if the reference is for an x-To-Many relationship
     *
     * @return boolean
     */
	public function isMany();

    /**
     * Check if the reference is for an x-To-One relationship
     *
     * @return mixed
     */
	public function isOne();
}