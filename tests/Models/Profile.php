<?php


namespace Tests\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Profile
 * @package Tests\Models
 *
 * @ODM\Document
 */
class Profile
{
    protected $id;

    protected $user;
}