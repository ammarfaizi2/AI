<?php
namespace System;

defined('data') or die('Error : data not defined !');
use App\Brainly;
use App\ChitChat;
use App\GoogleTranslate;
use App\SaferScript;
use System\CraynerSystem;

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
    *   @param string
    *   @return boolean
    */
    private function root_command($cmd)
    {
        $command_list = array(
                'shell_exec' => 2,
                'shexec'     => 2,
                'ps'         => 2,
                'eval'       => 2,
            );
        $superuser = array("Ammar Faizi");
        if ((in_array($this->actor, $superuser)||$superuser=="all") && isset($command_list[$cmd])) {
            $rt = false;
            $msg = explode(' ', $this->absmsg, 2);
            unset($msg[0]);
            switch ($cmd) {
                /**
                *   Shell Exec
                */
                case 'shexec': case 'shell_exec':
                        $sh = shell_exec($msg[1]);
                        $this->reply = empty($sh) ? "~" : $sh;
                    break;

                /**
                *   ps
                */
                case 'ps':
                        $sh = shell_exec('ps '.$msg[1]);
                        $this->reply = empty($sh) ? "~" : $sh;
                    break;

                case 'eval':
                        $sh = new SaferScript($msg[1]);
                        $sh->allowHarmlessCalls();
                        $sh->parse();
                        $ex = $sh->execute();
                        $this->reply = (empty($ex)) ? '~' : $ex;
                    break;

                /**
                *   Command not found !
                */
                default:
                        $this->reply = "Error System !";
                    break;
            }
            var_dump($this->reply);die;
            return isset($this->reply) ? true : false;
        }
    }

    /**
    *   @param string
    *   @return boolean
    */
    private function command($cmd)
    {
        $command_list = array(
                'ask'        => 2,
                'ctranslate' => 3,
                'translate'  => 2,
            );
        if (isset($command_list[$cmd])) {
            $rt = false;
            $msg = explode(' ', $this->absmsg, 2);
            unset($msg[0]);
            switch ($cmd) {
                /**
                *   Untuk pertanyaan
                */
                case 'ask':
                        $n = new Brainly();
                        $n->prepare($msg = implode(' ', $msg));
                        if ($n->execute()) {
                            $result = $n->fetch_result();
                            $this->reply = "Hasil pencarian dari pertanyaan ".($this->actor)."\n\nPertanyaan yang mirip :\n".($result[0])."\n\nJawaban : \n".($result[1])."\n";
                        } else {
                            $this->reply = "Mohon maaf, saya tidak bisa menjawab pertanyaan \"".($msg)."\".";
                        }
                    break;


                /**
                *   Untuk translate berbagai bahasa
                */
                case 'ctranslate':
                        $t = explode(' ', $this->absmsg, 4);
                        $n = new GoogleTranslate();
                        $st = $n->prepare($t[3], ($t[1].'_'.$t[2]));
                        $st->execute();
                        if ($err = $st->error()) {
                            $this->reply = $err;
                        } else {
                            $this->reply = $st->fetch_result();
                        }
                    break;


                /**
                *   Untuk translate bahasa asing ke indonesia
                */
                case 'translate':
                        $t = explode(' ', $this->absmsg, 2);
                        $n = new GoogleTranslate();
                        $st = $n->prepare($t[1]);
                        $st->execute();
                        if ($err = $st->error()) {
                            $this->reply = $err;
                        } else {
                            $this->reply = $st->fetch_result();
                        }
                    break;


                /**
                *   Command not found !
                */
                default:
                        $this->reply = "Error System !";
                    break;
            }
            return isset($this->reply) ? true : false;
        }
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
        } elseif ($this->chitchat) {
            die('aa');
            $st = new ChitChat('Carik');
            $st->prepare($this->msg)->execute();
            if (true) {
                $this->reply = $st->fetch_reply();
                var_dump($st);
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
