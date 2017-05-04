<?php
namespace System;
defined('data') or die('Error : data not defined !');
use System\Crayner_System;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class AI extends Crayner_System
{
    const data = '/ai/';
    private $msg;
    private $absmsg;
    private $actor;
    public function __construct()
    {
        is_dir(data.self::data) or mkdir(data.self::data);
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
