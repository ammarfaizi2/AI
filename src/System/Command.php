<?php
namespace System;

use App\Brainly;
use App\ChitChat;
use App\WhatAnime;
use App\SaferScript;
use App\GoogleTranslate;

trait Command
{
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
                'whatanime'  => 2,
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

                case 'whatanime':
                        $t = new WhatAnime(trim($msg[1]));
                        $t->execute();
                        $result = $t->fetch_result();
                        $reply = '';
                        foreach ($result['docs'][0] as $key => $value) {
                            $reply .= ucwords(str_replace("_", " ", $key))." : ".$value."\n";
                        }
                        $this->reply = $reply;
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
}
