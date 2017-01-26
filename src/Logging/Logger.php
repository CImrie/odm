<?php


namespace CImrie\ODM\Logging;


abstract class Logger
{
    /**
     * Should return a closure that can be called to log something
     *
     * @return \Closure
     */
    public function closure() {
        return function(array $log) {
            return $this->log($log);
        };
    }

    abstract public function log(array $log);
}