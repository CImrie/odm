<?php


namespace CImrie\ODM\Logging;


interface Loggable
{
    /**
     * Should return a closure that can be called to log something
     *
     * @return \Closure
     */
    public function closure();

    public function log(array $log);
}