<?php


namespace CImrie\ODM\Mapping\Traits;


use CImrie\ODM\Mapping\Discriminator;

trait DiscriminatorMap
{
    /**
     * @var Discriminator
     */
    protected $discriminator;

    public function discriminate()
    {
        $this->discriminator = new Discriminator();

        return $this->discriminator;
    }

    public function asArray()
    {
        if(!$this->discriminator)
        {
            $this->discriminator = new Discriminator();
        }

        return array_merge($this->mapping, $this->discriminator->asArray());
    }
}