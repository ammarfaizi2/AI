<?php
namespace AI;

use App\Brainly;
use App\ChitChat;
use AI\PHPVirtual;
use App\WhatAnime;
use App\MyAnimeList;
use App\SaferScript;
use App\JadwalSholat;
use Teacrypt\Teacrypt;
use App\GoogleTranslate;

/**
 *   @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

trait Command
{
    private $command_list = array(
        '<?php'      => 2,
        'ask'        => 2,
        'menu'       => 2,
        'jadwal'     => 2,
        'hitung'     => 2,
        'i_anime'    => 2,
        'i_manga'    => 2,
        'q_anime'    => 2,
        'q_manga'    => 2,
        'teacrypt'   => 2,
        'translate'  => 2,
        'whatanime'  => 2,
        'ctranslate' => 3,
    );
    /**
     *   @param string
     *   @return boolean
     */
    private function command($cmd)
    {
        if (isset($this->command_list[$cmd])) {
            $rt = false;
            $msg = explode(' ', $this->absmsg, 2);
            unset($msg[0]);
            switch ($cmd) {
            case '<?php':
                    $st = new PHPVirtual($this->absmsg);
                    $st->execute();
                    $r = $st->show_result();
                    $this->reply = $r ? $r : "blank result";
                break;  
                /**
                *   Untuk pertanyaan
                */
            case 'ask':
                if (!isset($msg[1]) or empty($msg[1])) {
                    $this->reply = "Mohon maaf, untuk bertanya silahkan ketik ask [spasi] pertanyaan\n\nKetik \"menu\" untuk melihat daftar perintah";
                } else {
                    $n = new Brainly();
                    $n->prepare($msg = implode(' ', $msg));
                    if ($n->execute()) {
                        $result = $n->fetch_result();
                        $this->reply = "Hasil pencarian dari pertanyaan ".($this->actor)."\n\nPertanyaan yang mirip :\n".($result[0])."\n\nJawaban : \n".($result[1])."\n";
                    } else {
                        $this->reply = "Mohon maaf, saya tidak bisa menjawab pertanyaan \"".($msg)."\".";
                    }
                }
                break;
                
                /**
                 *   Show menu
                 */
            case 'menu':
                    $this->reply = "Menu : \n1. ask [spasi] pertanyaan : Untuk bertanya\n2. menu : Untuk menampilkan menu ini\n3. ctranslate [spasi] from [spasi] to [spasi] kalimat : Untuk translate dari berbagai bahasa\n4. translate [spasi] kalimat : Untuk translate dari bahasa apapun ke bahasa Indonesia\n5. whatanime [spasi] url_gambar : Untuk mencari judul anime berdasarkan gambar";
                break;

                /**
                 *   Jadwal
                 */
            case 'jadwal':
                $msg = explode(" ", strtolower($msg[1]));
                switch ($msg[0]) {
                case 'sholat': case 'solat': case 'shalat':
                            $st = new JadwalSholat();
                            $get_kota = ucfirst(strtolower(trim($msg[1])));
                            if ($jadwal = $st->get_jadwal($get_kota)) {
                                $ret = "Jadwal Sholat untuk daerah {$get_kota} dan sekitarnya\nTanggal ".(date("d F Y"))."\n\n";
                                $jadwal = array_merge(array('imsyak'=>(date("h:i", strtotime($jadwal['subuh'])-300))), $jadwal);
                                foreach ($jadwal as $key => $jam) {
                                    $ret .= ucfirst($key) . " : " . $jam . "\n";
                                }
                                $this->reply = $ret;
                            } else {
                                if ($suggest_kota = self::jadwal_sholat_suggest($st->get_list_kota(), $get_kota)) {
                                    if (is_array($suggest_kota)) {
                                        if ($jadwal = $st->get_jadwal($suggest_kota[0])){
                                            $ret = "Jadwal Sholat untuk daerah {$suggest_kota[0]} dan sekitarnya\nTanggal ".(date("d F Y"))."\n\n";
                                            $jadwal = array_merge(array('imsyak'=>(date("h:i", strtotime($jadwal['subuh'])-300))), $jadwal);
                                            foreach ($jadwal as $key => $jam) {
                                                $ret .= ucfirst($key) . " : " . $jam . "\n";
                                            }
                                            $this->reply = $ret;
                                        }
                                    } else {
                                        $this->reply = "Mohon maaf, jadwal sholat kota \"{$get_kota}\" tidak ditemukan. Mungkin yang anda maksud adalah kota {$suggest_kota}";
                                    }
                                } else {
                                    $this->reply = "Mohon maaf, jadwal sholat kota \"{$get_kota}\" tidak ditemukan.";
                                }
                            }
                    break;
                        
                default:
                        $this->reply = null;
                    break;
                }
                break;


                /**
                 *   Hitung
                 */
            case 'hitung':
                if (!isset($msg[1])) {
                    $this->reply = "Untuk menghitung, ketik 'hitung [spasi] perhitungan'\n\nContoh :\nhitung 100+100";
                } else {
                    $a = array('x','=','?');
                    $b = array('*','','');
                    $st = new SaferScript("\$q = ".str_replace($a, $b, $msg[1]));
                    $st->allowHarmlessCalls(true);
                    if (count($st->parse())) {
                        $this->reply = "Perhitungan tidak ditemukan !";
                    } else {
                        $this->reply = $st->execute();
                    }
                }
                break;

                /**
                 *   Mencari ID Anime
                 */
            case 'q_anime': case 'q_manga':
                    $search = (new MyAnimeList('ammarfaizi2', 'triosemut123'))->search($msg[1], $cmd);
                    $this->reply = $search ? ($search)."\n\nUntuk mencari info anime ketik \"i_anime [spasi] id_anime\"\nContoh :\ni_anime 100" : "Mohon maaf anime \"".$msg[1]."\" tidak ditemukan !";
                break;

                /**
                 *   Untuk mencari info anime
                 */
            case 'i_anime': case 'i_manga':
                    $msg[1] = trim($msg[1]);
                    if (is_numeric($msg[1])) {
                        $search = (new MyAnimeList('ammarfaizi2', 'triosemut123'))->get_info($msg[1], $cmd);
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
                 *  Enkripsi dan Dekripsi Teacrypt
                 */
            case 'teacrypt':
                $msg = explode(" ", $this->absmsg);
                if (strtolower($msg[1]) == "enc") {
                    if (!isset($msg[3]) or empty($msg[3])) {
                        $this->reply = "Key harus diisi !";
                    } else {
                        $this->reply = Teacrypt::encrypt($msg[2], $msg[3]);
                    }
                } elseif (strtolower($msg[1]) == "dec") {
                    if (!isset($msg[3]) or empty($msg[3])) {
                        $this->reply = "Key harus diisi !";
                    } else {
                        $this->reply = Teacrypt::decrypt($msg[2], $msg[3]);
                    }
                } else {
                    $this->reply = "Perintah tidak dikenal !";
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






    /**
     * Extend method
     */
    private static function jadwal_sholat_suggest($list_jadwal, $kota_request)
    {
        foreach ($list_jadwal as $key => $value) {
            $count_diff = levenshtein($key, $kota_request);
            $list[$key] = $count_diff;
            if ($count_diff < 5 && !isset($pick_suggest)) {
                $pick_suggest = true;
            }
        }
        if (isset($pick_suggest) && $pick_suggest) {
            $min = min($list);
            $get_mirip = array_search($min, $list);
            $get_kota_mirip = $min < 3 ? array($get_mirip) : $get_mirip;
        }
        return isset($get_kota_mirip) ? $get_kota_mirip : false;
    }

    private static function trigonometri()
    {
        /**
         *
         * Mini trigonometri
         *
         */
        $trigonometri['sin'] = array(
                        0  => "0",
                        30 => "(1/2)",
                        45 => "(1/2)*sqrt(2)",
                        60 => "(1/2)*sqrt(3)",
                        90 => "1",
                    );
        $trigonometri['cos'] = array(
                        0  => "1",
                        30 => "(1/2)*sqrt(3)",
                        45 => "(1/2)*sqrt(2)",
                        60 => "(1/2)",
                        90 => "0"
                    );
        $trigonometri['tan'] = array(
                        0  => "0",
                        30 => "(1/3)*sqrt(3)",
                        45 => "1",
                        60 => "sqrt(3)",
                        90 => "~"
                    );
    }
}
