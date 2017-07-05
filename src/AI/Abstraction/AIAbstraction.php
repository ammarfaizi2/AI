<?php

namespace AI\Abstraction;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

abstract class AIAbstraction
{
    /**
     * Constructor.
     */
    abstract public function __construct();

    /**
     * Time utilities.
     */
    abstract public function set_timezone(string $timezone);
    protected function fdate(string $timechars)
    {
    }


    /**
     * Message management.
     */
    abstract public function prepare(string $text);
    abstract public function execute();
    abstract public function fetch_reply();


    /**
     * Error Info.
     */
    abstract public function errorInfo();


    /**
     * Magic.
     */
    abstract public function __debugInfo();
    abstract public function __toString();
    abstract public function __destruct();
}
