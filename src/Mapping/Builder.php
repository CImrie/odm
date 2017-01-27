<?php


namespace CImrie\ODM\Mapping;


interface Builder
{
    /**
     * Returns an array representation of the reference
     * ready for inclusion in the Class Metadata
     *
     * @return array
     */
    public function asArray();
}