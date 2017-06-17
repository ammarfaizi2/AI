<?php

namespace AI;

/**
 *   @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

trait Chat
{
    /**
     * Wordlist
     *
     * @var array
     */
    private $wl = array();

    /**
     * Timereply memory
     *
     * @var array
     */
    private $timereply;

    /**
     *
     *
     * @var float
     */
    private $similarity_minimal = 65.0;

    /**
     *
     *
     * @var array
     */
    private $similar_word_temporary;

    /**
    *   Wordlist loader
    */
    private function load_wordlist()
    {
        $this->msg = strip_tags($this->msg);
        $this->wl = array(

            "hai,hay,hi,hy"=>array(
            1,array(
                    "Hai juga ^@. Apa kabar?",
                    "Hay juga ^@. Apa kabar?"
            ),true,5,25,null,false),

            "halo,hallo,allo,helo,hola,alo,ello"=>array(
            1,array(
                    "Halo juga kang ^@ :)",
                    "Halo juga kang ^@",
                    "Halo juga kak ^@"
            ),false,10,50,null,false),

            /**
             *  Tanya kabar.
             */
            "pa+kabar,pa+kbr,pa+kbar"=>array(
            1,array(
                    "Kabar baik disini.",
                    "Kabar baik, kang ^@ apa kabar?"
            ),false,10,50,null,false),

            /**
             *  Menanyakan jam (Bahasa Inggris)
             */
            "what+time"=>array(
            1,array(
                    "Time #d(jam_pq)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan jam (Bahasa Indonesia)
             */
            "jam+ber,jam+brp,jam+pro,jam+piro"=>array(
            1,array(
                    "0-11"=>array("Sekarang jam #d(jam) pagi"),
                    "11-14"=>array("Sekarang jam #d(jam) siang"),
                    "14-17"=>array("Sekarang jam #d(jam) sore"),
                    "18-24"=>array("Sekarang jam #d(jam) malam")
            ),false,10,50,null,true),

            /**
             *  Menanyakan hari besok.
             */
            "besok+hari+apa"=>array(
            1,array(
                    "besok hari #d(day+1day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan hari kemarin.
             */
            "kemarin+hari+apa"=>array(
            1,array(
                    "kemarin hari #d(day-1day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan hari sekarang.
             */
            "hari+apa"=>array(
            1,array(
                    "sekarang hari #d(day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan bulan sekarang.
             */
            "bulan+apa"=>array(
            1,array(
                    "sekarang bulan #d(bulan)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan tanggal kemarin.
             */
            "kemarin+tanggal+brp,kemarin+tanggal+berapa,kemarin+tgl+brp,kemarin+tgl+berapa"=>array(
            1,array(
                    "Kemarin tanggal #d(date_c-1day)"
            ),false,10,50,null,false),

            /**
             *  Menanyakan tanggal besok.
             */
            "besok+tanggal+brp,besok+tanggal+berapa,besok+tgl+brp,besok+tgl+berapa"=>array(
            1,array(
                    "Besok tanggal #d(date_c+1day)"
            ),false,10,50,null,false),


            /**
             *  Menanyakan tanggal sekarang.
             */
            "tanggal+brp,tanggal+berapa,tgl+brp,tgl+berapa"=>array(
            1,array(
                    "Sekarang tanggal #d(date_c)"
            ),false,10,50,null,false),


            /**
             *  Sapaan di pagi hari.
             */
            "pagi"=>array(
            1,array(
                    "1-10"=>array(
                        "Selamat pagi kang ^@. Selamat beraktifitas."
                    ),
                    "11-12"=>array(
                        "Selamat pagi menjelang siang kang ^@"
                    ),
                    "13-14"=>array(
                        "Ini udah siang kang ^@ 😌"
                    ),
                    "15-18"=>array(
                        "Ini udah sore kang ^@ 😌"
                    ),
                    "19-23,0"=>array(
                        "Ini sudah malem kang ^@ 😌"
                    )
            ),false,8,35,null,true),

            /**
             *  Sapaan di siang hari.
             */
            "siang,ciang,siank"=>array(
            1,array(
                    "0-10"=>array(
                        "Ini masih pagi lho kang ^@ 😌"
                    ),
                    "11-14"=>array(
                        "Selamat siang kang ^@, selamat beraktifitas."
                    ),
                    "15-18"=>array(
                        "Ini udah sore kang ^@ 😌"
                    ),
                    "19-24"=>array(
                        "Ini udah malem kang ^@ 😌"
                    )
            ),false,8,35,null,true),

            /**
             *  Sapaan di sore hari.
             */
            "sore"=>array(
            1,array(
                    "0-10"=>array(
                        "Ini masih pagi lho kang ^@ 😌"
                    ),
                    "11-13"=>array(
                        "Ini masih siang lho kang ^@"
                    ),
                    "14-18"=>array(
                        "Selamat sore kang ^@, selamat beristirahat."
                    ),
                    "19-24"=>array(
                        "Ini udah malem kang ^@ 😌"
                    )
            ),true,8,35,null,true),


            /**
             *  Sapaan di malam hari.
             */
            "malem,malam"=>array(
            1,array(
                    "0-4"=>array(
                        "Ini udah pagi kang ^@, selamat pagi, selamat beraktifitas."
                    ),
                    "5-10"=>array(
                        "Ini masih pagi kang ^@"
                    ),
                    "11-14"=>array(
                        "Ini masih siang kang ^@",
                    ),
                    "15-18"=>array(
                        "Ini masih sore loh kang ^@"
                    ),
                    "19-23"=>array(
                        "Selamat malam kang ^@, selamat beristirahat."
                    )
            ),true,9,65,null,true),


            /**
             *  Sapaan di pagi hari.
             */
            "ohayo"=>array(
            1,array(
                    "0-9,24"=>array(
                        "Ohayou kang ^@, selamat beraktiftas 😙"
                    ),
                    "10-11"=>array(
                        "Selamat pagi menjelang siang ^@"
                    ),
                    "12-14"=>array(
                        "Ini udah siang kang ^@ :v"
                    ),
                    "15-18"=>array(
                        "Ini udah sore kang ^@"
                    ),
                    "19-23"=>array(
                        "Ini udah malem kang ^@"
                    )
            ),false,9,65,null,true),

            /**
             *  Sapaan di siang hari.
             */
            "koniciwa,konnichiwa,konichiwa,konniciwa"=>array(
            1,array(
                    "0-9,24"=>array(
                        "Ini masih pagi kang ^@"
                    ),
                    "10-18"=>array(
                        "Konnichiwa kang ^@, selamat beraktifitas"
                    ),
                    "19-23"=>array(
                        "Ini udah malem kang ^@"
                    )
            ),false,9,90,null,false),


            /**
             *  Sapaan di sore hari.
             */
            "konbawa,konbanwa"=>array(
            1,array(
                    "0-9,24"=>array(
                        "ini masih pagi kang ^@"
                    ),
                    "10-23"=>array(
                        "konbanwa kang ^@"
                    )
            ),true,8,65,null,true),

            /**
             *  Tertawa.
             */
            "haha,hihi,wkwk,wkeke,hhh"=>array(
            1,array(
                    "Dilarang ketawa !"
            ),false,10,75,null,false),

            /**
             *  Move on.
             */
            "move+on"=>array(
            1,array(
                    "Move on adalah jalan terbaik kak ^@, kamu harus kuat :)",
                    "Jangan mudah percaya dengan orang lain, move on itu susah.",
                    "Selamat move on.\n\nUdah itu aja."
            ),true,8,75,null,false),

            "jomblo"=>array(
            1,array(
                    "Ciyaah... @ jomblo nih 😂😂",
                    "Ciye jomblo :v",
                    "Ciye ^@ jomblo 😏"
            ),false,9,90,null,false),


            "larang"=>array(
                1,array(
                    "Wah ngelarang larang nih kang ^@ 😏",
                    "Kang ^@, mau dilarang?"
                ),false,7,45,null,false),


                "laper,lapar,lavar"=>array(
                1,array(
                "0-3"=>array(
                    "Segera sahur kang ^@",
                    "Sahur dulu kang ^@ 😊"
                ),
                "4-15"=>array(
                    "Sabar kang ^@, belum waktunya berbuka 😇",
                    "Sabar ya kang ^@, kita tunggu sampai waktunya berbuka."
                ),
                "16-17"=>array(
                    "Sabar kang ^@, bentar lagi magrib kok 😏",
                    "Sabar aja ya kang ^@, sebentar lagi udah magrib 😋😏"
                ),
                "18-24"=>array(
                    "Kalau laper ya makan 😊",
                    "Makan gamping dong. *eeehhhh",
                    "Makan tanah dong. *eeeehhh",
                    "Makan aeeh :v"
                )
                ),false,10,75,null,true),


                "bot,"=>array(
                1,array(
                    "Ya, ada apa kang ^@?",
                ),true,2,5,null,true),




                /**
             * Check 2
             */
                "assalamualaikum,asalamualaikum"=>array(
                2,array(
                    "Waalaikumsalam"
                ),
                80,10,75,null,false),


        );
    }

    /**
     *  Chat action
     */
    private function chat()
    {
        $this->load_wordlist();
        $this->exms = explode(' ', $this->msg);
        $this->cword = count($this->exms);
        $this->mslg = strlen($this->msg);
        foreach ($this->wl as $key => $val) {
            if ($this->{"check{$val[0]}"}($key, $val[2], $val[3], $val[4], $val[5], $val[6])) {
                if ($this->timereply) {
                    if ($this->gettimereply($val[1])) {
                        $val[1] = $this->timereply;
                    } else {
                        return false;
                    }
                }
                $act = explode(" ", $this->actor);
                $this->reply = $this->fdate(str_replace("@", $this->actor, str_replace("^@", $act[0], $val[1][rand(0, count($val[1])-1)])));
                return true;
            }
        }
        if (count($this->similar_word_temporary)) {
            $max_key = array_search(max($this->similar_word_temporary), $this->similar_word_temporary);
            $this->reply = $this->wl[$max_key][1][rand(0, count($this->wl[$max_key][1])-1)];
        }
        return isset($this->reply);
    }
        
    /**
     * Check chat
     *
     * @param  string $key
     * @param  bool   $wordcheck
     * @param  int    $maxwords
     * @param  int    $maxlength
     * @param  array  $wordexception
     * @param  bool   $time
     * @return bool
     */
    private function check1(string $key, bool $wordcheck=false, int $maxwords=null, int $maxlength=null, array $wordexception=null, bool $time=false)
    {
        if (($maxlength!==null and ($this->mslg) > $maxlength) or ($maxwords!==null and ($this->cword) > $maxwords)) {
            return false;
        }
        if (is_array($wordexception)) {
            foreach ($wordexception as $qw) {
                if (in_array($qw, $this->exms)) {
                    return false;
                }
            }
        }

        $ex = explode(',', $key);

        if ($wordcheck) {
            $stop = false;
            foreach ($ex as $qw) {
                $a = explode('+', $qw);
                $notwr = true;
                foreach ($a as $qw2) {
                    if (!in_array($qw2, $this->exms)) {
                        $notwr = false;
                        break;
                    }
                }
                if ($notwr) {
                    if ($time) {
                        $this->timereply = true;
                    }
                    return true;
                }
            }
        } else {
            $stop = false;
            foreach ($ex as $qw) {
                $a = explode('+', $qw);
                $notwr = true;
                foreach ($a as $qw2) {
                    if (strpos($this->msg, $qw2)===false) {
                        $notwr = false;
                        break;
                    }
                }
                if ($notwr) {
                    if ($time) {
                        $this->timereply = true;
                    }
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check chat 2
     *
     * @param  string $key
     * @param  int    $minimal
     * @param  int    $maxwords
     * @param  int    $maxlength
     * @param  array  $wordexception
     * @param  bool   $time
     * @return bool
     */
    private function check2(string $key, int $minimal=null, int $maxwords=null, int $maxlength=null, array $wordexception=null, bool $time=false)
    {
        if (($maxlength!==null and ($this->mslg) > $maxlength) or ($maxwords!==null and ($this->cword) > $maxwords)) {
            return false;
        }
        if (is_array($wordexception)) {
            foreach ($wordexception as $qw) {
                if (in_array($qw, $this->exms)) {
                    return false;
                }
            }
        }

        $ex = explode(',', $key);
        $_similar_1 = array();
        $_different_1 = array();
        foreach ($ex as $wlist) {
            $similar_0 = array();
            $_different_0 = array();
            foreach ($this->exms as $in) {
                similar_text($wlist, $in, $percent);
                $_different_0[] = levenshtein($wlist, $in);
                $similar_0[] = $percent;
            }
            $count = count($this->exms);
            $_different_1[] = array_sum($_different_0) / $count;
            $_similar_1[] = max($similar_0);
        }
        $average = (array_sum($_similar_1) / count($ex) * max($similar_0) - array_sum($_different_1))/100;
        if ($average >= $this->similarity_minimal and ($minimal===null or $average >= $minimal)) {
            $this->similar_word_temporary[$key] = $average;
        }
        return false;
    }


    /**
     * Get time reply
     *
     * @param  array $replylist
     * @return bool
     */
    private function gettimereply(array $replylist)
    {
        foreach ($replylist as $time => $replist) {
            $tr = array();
            $a = explode(",", $time);
            foreach ($a as $b) {
                $c = explode("-", $b);
                if (count($c)==1) {
                    $tr[] = $c[0];
                } else {
                    $tr = array_merge($tr, range($c[0], $c[1]));
                }
                if (in_array((int)date("H"), $tr)) {
                    $this->timereply = $replist;
                    return true;
                }
            }
        }
        return false;
    }
}
