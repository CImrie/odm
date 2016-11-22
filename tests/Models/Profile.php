<?php


namespace Tests\Models;

use CImrie\ODM\Configuration\MetaData\Annotations as ODM;

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