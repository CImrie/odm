<?php


namespace CImrie\ODM\Mapping\References;


interface Reference {

	/**
	 * Returns an array representation of the reference
	 * ready for inclusion in the Class Metadata
	 *
	 * @return array
	 */
	public function asArray();

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