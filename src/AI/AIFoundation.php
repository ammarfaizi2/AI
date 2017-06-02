<?php

namespace AI;

use AI\Hub\Singleton;

abstract class AIFoundation
{
    use Singleton;
    abstract public function execute();
}
