<?php

namespace AI;

use AI\Hub\Singleton;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

abstract class CraynerSystem
{
    private function clog()
    {
    }

    private function chat()
    {
    }

    private function command()
    {
    }

    private function root_command()
    {
    }

    abstract public function set_timezone(string $timezone);
    abstract public function prepare(string $text);
    abstract public function execute();
    abstract public function fetch_reply();
}
