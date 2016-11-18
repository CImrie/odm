<?php


namespace LaravelDoctrine\ODM\Mapping;

use LaravelDoctrine\ODM\Mapping\References\Many;
use LaravelDoctrine\ODM\Mapping\References\One;


class Reference {

    const DB_REF_WITH_DB_NAME = 'dbRefWithDb';
    const DB_REF_WITHOUT_DB_NAME = 'dbRef';
    const DB_REF_ID_ONLY = 'id';

    public function one($property, $entity)
    {
        $builder = new One();
        $builder->property($property)
            ->entity($entity)
            ;

        return $builder;
    }

    /**
     * Start mapping a 'ReferenceMany' reference.
     * Entity is optional as ODM allows document type mixing.
     *
     * @param $property
     * @param null $entity
     * @return Many
     */
    public function many($property, $entity = null)
    {
        $builder = new Many();
        $builder->property($property);

        if($entity){
            $builder->entity($entity);
        }

        return $builder;
    }
}