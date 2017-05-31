<?php
namespace AI\Exceptions;

use AI\AI;
use Exception;

/**
 *
 * @author	Ammar Faizi	<ammarfaizi2@gmail.com>
 */

class AIException extends Exception
{
    public function __construct($msg='', $code=0)
    {
    	$this->error_log = data . AI::DATA . '/error_log';
        parent::__construct($msg, $code);
    }
    
    public function __toString()
    {
    	$msg = __CLASS__ .": [{$this->code}]: {$this->message}";
    	file_put_contents($this->error_log, "\n[".date("Y m d h:i:s A")."]\t{$msg} in {$this->file} on line {$this->line}\n", FILE_APPEND | LOCK_EX);
        return $msg;
    }

    private function log()
    {

    }
}
