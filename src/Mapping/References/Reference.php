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
}