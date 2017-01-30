<?php


namespace CImrie\ODM\Laravel\Traits;


trait OdmConfig
{
    /**
     * @param string | null $key
     * @param mixed | null $default
     * @return mixed
     */
    protected function getConfig($key = null, $default = null)
    {
        $key = $key !== null ? '.' . $key : null;

        return $this->getGlobalConfig($this->getConfigName() . $key, $default);
    }

    /**
     * @param string | null $key
     * @param mixed| null $default
     * @return mixed
     */
    protected function getGlobalConfig($key = null, $default = null)
    {
        $config = $this->app->make('config')->all();

        if ($key) {
            $config = array_get($config, $key, $default);
        }

        return $config;
    }

    /**
     * @return string
     */
    protected function getConfigName()
    {
        return 'odm';
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return __DIR__ . '/../../../config/' . $this->getConfigName() . '.php';
    }
}