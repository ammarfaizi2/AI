<?php
namespace AI;

defined('data') or die('Error : data not defined !');
use AI\CraynerSystem;

/**
* @package  AI
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
*/

class AI extends CraynerSystem
{
    const VERSION = "1.0";
    const data = '/ai/';

    /**
     * Message in lower case
     *
     * @var  string
     */
    private $msg;

    /**
     * Absolute message
     *
     * @var  string
     */
    private $absmsg;

    /**
     * Actor name
     *
     * @var  string
     */
    private $actor;

    /**
     * ChitChat string
     * 
     * @deprecated
     * @var  string
     */
    private $chitchat;

    /**
     * AI Reply
     *
     * @var  string
     */
    private $reply;

    /**
     * Constructor
     *
     * @author Ammar Faizi <ammarfaizi2@gmail.com>
     */
    public function __construct()
    {
        /**
         *
         * Create directory for AI data  
         *
         */
        is_dir(data.self::data) or mkdir(data.self::data);
        is_dir(data.self::data.'/logs') or mkdir(data.self::data.'/logs');
        is_dir(data.self::data.'/status') or mkdir(data.self::data.'/status') and file_put_contents(data.self::data.'/status/chit_chat_on', '1');
        is_dir(data.self::data.'/chat_logs') or mkdir(data.self::data.'/chat_logs');

        /**
         * ChitChat directory
         * @deprecated
         */
        $this->chitchat = file_exists(data.self::data.'/status/chit_chat_on');
    }

    /**
     * Load Traits
     */
    use RootCommand, Command, Chat;
    
    /**
     * Chat Log
     *
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
     * @param    string  $text
     * @param    string  $actor
     * @return   object  AI Instance
     */
    public function prepare(string $text, $actor=null)
    {
        $this->msg      = trim(strtolower($text));
        $this->absmsg   = $text;
        $this->actor    = $actor;
        return $this;
    }

    /**
     *   @return bool
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
