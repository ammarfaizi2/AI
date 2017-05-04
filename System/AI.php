<?php
namespace System;
use System\Crayner_System;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class AI extends Crayner_System
{
    private $msg;
    private $absmsg;
    private $actor;
    public function __construct()
    {
        
    }
    /**
    *   @param string,string
    */
    public function prepare($text,$actor=null)
    {
        $this->msg      = trim(strtolower($text));
        $this->absmsg   = $text;
        $this->actor    = $actor;
        return $this;
    }
    public function status()
    {

    }
    public function execute()
    {

    }
}
