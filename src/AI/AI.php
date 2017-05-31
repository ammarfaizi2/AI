<?php
namespace AI;

defined('data') or die('Error : data not defined !');

use AI\Chat;
use AI\Command;
use AI\Hub\AIProp;
use AI\Hub\AIFace;
use AI\RootCommand;
use AI\Hub\ChatFace;
use AI\Hub\Singleton;
use AI\CraynerSystem;
use AI\Exceptions\AIException;

/**
 * @package  AI
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

class AI extends CraynerSystem implements AIFace, AIProp
{
    const DATA              = '/ai/';
    const VERSION           = "1.3";
    const ERROR_INFO        = 4;
    const ERROR_EXCEPTION   = 402;
    const DEFAULT_TIMEZONE  = "Asia/Jakarta";


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
     * Timezone
     *
     * @var string 
     */
    private $timezone;

    /**
     * Load Traits
     */
    use RootCommand, Command, Chat, Singleton;



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
         */
        (is_dir(data . self::DATA) or mkdir(data . self::DATA)) xor 
        (is_dir(data . self::DATA.'/logs') or mkdir(data . self::DATA . '/logs')) xor 
        (is_dir(data . self::DATA.'/status') or (mkdir(data . self::DATA . '/status') and 
        (file_put_contents(data.self::DATA . '/status/chit_chat_on', '1')))) xor 
        (is_dir(data . self::DATA . '/chat_logs') or mkdir(data . self::DATA . '/chat_logs'));

        if (!is_dir(data . self::DATA)) {
            throw new AIException("Cannot create data folder", self::ERROR_EXCEPTION);
            
        }

        /**
         * ChitChat directory
         * @deprecated
         */
        $this->chitchat = file_exists(data.self::DATA.'/status/chit_chat_on');
    }
    

    /**
     * Chat Log
     *
     */
    private function clog()
    {
        $file = data.self::DATA.'/chat_logs/'.date('Y-m-d').'.txt';
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
     * Set timezone for AI
     *
     * @param   string  $timezone
     */
    public function set_timezone(string $timezone)
    {
        if ($timezone) {
            $this->timezone = $timezone;
            date_default_timezone_set($timezone);
        }
    }


    /**
     * @param    string  $text
     * @param    string  $actor
     * @return   object  AI Instance
     */
    public function prepare(string $text, string $actor=null)
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
        if (!isset($this->absmsg)) {
            throw new AIException("Cannot access execute method directly, you must prepare a message first", );
        }

        if (!isset($this->timezone)) {
            $this->set_timezone(self::DEFAULT_TIMEZONE);
        }
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
