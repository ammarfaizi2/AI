<?php

namespace System\Exceptions;

use Exception;

class AIException extends Exception
{
    public function __construct($msg='', $code=0)
    {
        parent::__construct($msg, $code);
    }
    
    public function __toString()
    {
        return __CLASS__ .": [{$this->code}]: {$this->message}";
    }
}
