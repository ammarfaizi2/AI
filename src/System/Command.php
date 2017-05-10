<?php
namespace System;

use App\Brainly;
use App\ChitChat;
use App\WhatAnime;
use App\MyAnimeList;
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
                'menu'       => 2,
                'i_anime'    => 2,
                'i_manga'    => 2,
                'q_anime'    => 2,
                'q_manga'    => 2,
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
                *   Show menu
                */
                case 'menu':
                        $this->reply = "Menu : \n1. ask[spasi]pertanyaan : Untuk bertanya\n2. menu : Untuk menampilkan menu ini\n3. ctranslate[spasi]from[spasi]to[spasi]kalimat : Untuk translate dari berbagai bahasa\n4. translate[spasi]kalimat : Untuk translate dari bahasa apapun ke bahasa Indonesia\n5. whatanime[spasi]url_gambar : Untuk mencari judul anime berdasarkan gambar";
                    break;

                /**
                *   Mencari ID Anime 
                */
                case 'q_anime': case 'q_manga':
                        $search = (new MyAnimeList('ammarfaizi2', 'triosemut123'))->search($msg[1],$cmd);
                        $this->reply = $search ? ($search)."\n\nUntuk mencari info anime ketik \"i_anime[spasi]id_anime\"\nContoh :\ni_anime 100" : "Mohon maaf anime \"".$msg[1]."\" tidak ditemukan !";
                    break;

                /**
                *   Untuk mencari info anime
                */
                case 'i_anime': case 'i_manga':
                        $msg[1] = trim($msg[1]);
                        if (is_numeric($msg[1])) {
                            $search = (new MyAnimeList('ammarfaizi2', 'triosemut123'))->get_info($msg[1],$cmd);
                            $this->reply = $search ? $search : "Mohon maaf, anime dengan id ".$msg[1]." tidak ditemukan !";
                        } else {
                            $this->reply = "Mohon maaf, pencarian info anime hanya bisa dilakukan dengan ID anime !";
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
                *   Mencari judul anime dengan URL gambar
                */
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
