<?php
namespace System;
defined('data') or die('Error : data not defined !');
use System\Crayner_System;
use App\ChitChat;

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
    private $data;
    private $chitchat;
    private $reply;
    public function __construct()
    {
        is_dir(data.self::data) or mkdir(data.self::data);
        $this->data = data.self::data;
        $this->chitchat = file_exists($this->data.'chit_chat_on');
    }

    /**
    *   @param string,string
    *   @return instance
    */
    public function prepare($text,$actor=null)
    {
        $this->msg      = trim(strtolower($text));
        $this->absmsg   = $text;
        $this->actor    = $actor;
        return $this;
    }

    /**
    *
    */
    public function status()
    {

    }

    /**
    *   @return boolean
    */
    public function execute()
    {
        if ($this->chitchat) {
            $st = new ChitChat('Carik');
            if($st->prepare($this->msg)->execute()){
                $this->reply = $st->fetch_reply();
                return $this->reply===null ? false : true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function fetch_reply()
    {
        return $this->reply;
    }
}
