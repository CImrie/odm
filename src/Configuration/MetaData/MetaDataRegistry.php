<?php


namespace CImrie\ODM\Configuration\MetaData;

class MetaDataRegistry
{
    /**
     * @var Metadata
     */
    protected $drivers = [];

    /**
     * MetaDataRegistry constructor.
     * @param Metadata[] $drivers
     */
    public function __construct(array $drivers)
    {
        $this->load($drivers);
    }

    /**
     * @param Metadata[] $drivers
     */
    private function load(array $drivers)
    {
        foreach($drivers as $driver)
        {
            $this->drivers[get_class($driver)] = $driver;
        }
    }

    /**
     * @param $driver
     * @return Metadata | null
     */
    public function get($driver)
    {
        return isset($this->drivers[$driver]) ? $this->drivers[$driver] : null;
    }
}