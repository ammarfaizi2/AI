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
        is_dir(data.self::data.'/status') or mkdir(data.self::data.'/status');
        is_dir(data.self::data.'/logs') or mkdir(data.self::data.'/logs');
        is_dir(data.self::data.'/chat_logs') or mkdir(data.self::data.'/chat_logs');
        $this->chitchat = file_exists(data.self::data.'/status/chit_chat_on');
    }

    /**
    * void
    */
    private function clog()
    {
        $file = data.self::data.'/chat_logs/'.date('Y-m-d').'.txt';
        $data = file_exists($file) ? json_decode(file_get_contents($file),true) : array();
        $data = $data===null ? array() : $data;
        $data[] = array(
                'time'  => (date('Y-m-d H:i:s')),
                'actor' => $this->actor,
                'msg'   => $this->absmsg,
                'reply' => $this->reply,
            );
        file_put_contents($file, json_encode($data,128));
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
    *   @return boolean
    */
    public function execute()
    {
        if ($this->chitchat) {
            $st = new ChitChat('Carik');
            if($st->prepare($this->msg)->execute()){
                $this->reply = $st->fetch_reply();
                $rt = $this->reply===null ? false : true;
            } else {
                $rt = false;
            }
        } else {
            $rt = false;
        }
        $this->clog();
        return $rt;
    }

    /**
    *   @return mixed
    */
    public function fetch_reply()
    {
        return $this->reply;
    }
}
