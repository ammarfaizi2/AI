<?php

namespace AI;

use AI\Core\CraynerCore;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

abstract class AIAbstraction extends CraynerCore
{
    abstract public function __construct();
    abstract public function set_timezone(string $timezone);
    abstract public function prepare(string $text);
    abstract public function execute();
    abstract public function fetch_reply();
    abstract public function __debugInfo();
    abstract public function __toString();
    abstract public function errorInfo();
    abstract public function __destruct();   
}