<?php
namespace AI;

defined('data') or die('Error : data not defined !');
use AI\CraynerSystem;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class AI extends CraynerSystem
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
        is_dir(data.self::data.'/logs') or mkdir(data.self::data.'/logs');
        is_dir(data.self::data.'/status') or mkdir(data.self::data.'/status') and file_put_contents(data.self::data.'/status/chit_chat_on', '1');
        is_dir(data.self::data.'/chat_logs') or mkdir(data.self::data.'/chat_logs');


        $this->chitchat = file_exists(data.self::data.'/status/chit_chat_on');
    }

    use RootCommand, Command, Chat;
    
    /**
    * void
    */
    private function clog()
    {
        $file = data.self::data.'/chat_logs/'.date('Y-m-d').'.txt';
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : array();
        $data = $data===null ? array() : $data;
        $data[] = array(
                'time'  => (date('Y-m-d H:i:s')),
                'actor' => $this->actor,
                'msg'   => $this->absmsg,
                'reply' => $this->reply,
            );
        file_put_contents($file, json_encode($data, 128));
    }

    /**
    *   @param string,string
    *   @return instance
    */
    public function prepare($text, $actor=null)
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
        $cmd = explode(' ', $this->msg, 2);
        $cmd = $cmd[0];
        if ($this->root_command($cmd)) {
            $rt = true;
        } elseif ($this->command($cmd)) {
            $rt = true;
        } /*elseif ($this->chitchat) {
            $st = new ChitChat('Carik');
            $st->prepare($this->msg)->execute();
            if (true) {
                $this->reply = $st->fetch_reply();
                var_dump($st);
                $rt = $this->reply===null ? false : true;
            } else {
                $rt = false;
            }
        }*/ else {
            $rt = $this->chat();
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
