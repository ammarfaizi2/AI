<?php
namespace AI;

/**
*		@author Ammar Faizi <ammarfaizi2@gmail.com>
*/
trait Chat
{
    /**
        *
        */
        private $wl;
    private $timereply;
    private function load_wordlist()
    {
        date_default_timezone_set("Asia/Jakarta");
            /**
            *
            * hai,hay,hi,hy
            *		mode,answer,wordcheck,maxwords,maxlength,wordexception,time
            */
            $this->msg = strip_tags($this->msg);
        $this->wl = array(
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
)),false,10,75,null,true),

"hai,hay,hi,hy"=>array(
1,array(
"Hai juga ^@. Apa kabar?",
"Hay juga ^@. Apa kabar?"
),true,5,25,null,false),

"halo,hallo,allo,helo,hola,alo,ello"=>array(
1,array(
"Halo juga kang ^@ :)",
"Halo juga kang ^@, apa kabar?",
"Halo juga kak ^@"
),true,8,65,null,false        ),

"pa+kabar,pa+kbr,pa+kbar"=>array(
1,array(
    "Kabar baik disini.",
    "Kabar baik, kang ^@ apa kabar?"
),false,8,35,null,false),
        
"jam+ber,jam+brp,jam+pro,jam+piro"=>array(
1,array(
"0-11"=>array("Sekarang jam #d(jam) pagi"),
"11-14"=>array("Sekarang jam #d(jam) siang"),
"14-18"=>array("Sekarang jam #d(jam) sore"),
"18-24"=>array("Sekarang jam #d(jam) malam")
),false,8,35,null,true),

"pagi"=>array(
1,array(
"1-10"=>array(
    "Selamat pagi kang ^@. Selamat beraktifitas."
),
"11-14"=>array(
    "Ini udah siang kang ^@ 😌"
),
"15-18"=>array(
    "Ini udah sore kang ^@ 😌"
),
"19-23,0"=>array(
    "Ini sudah malem kang ^@ 😌"
)),false,8,35,null,true),

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
)),false,8,35,null,true),

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
)),true,8,35,null,true),

"malem,malam"=>array(
1,array(
"0-4"=>array(
    "Selamat pagi kang ^@."
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
"19-24"=>array(
    "Selamat malam kang ^@, selamat beristirahat."
)),true,9,65,null,true),

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
)),false,9,65,null,true),

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
)),false,9,90,null,false),

"konbawa,konbanwa"=>array(
1,array(
"0-9,24"=>array(
    "ini masih pagi kang ^@"
),
"10-23"=>array(
    "konbanwa kang ^@"
)),true,8,65,null,true),

"haha,hihi,wkwk,wkeke,hhh"=>array(
1,array(
"Dilarang ketawa !\nhahaha",
"Hahaha ketawa",
"Sadess :v"
),false,10,75,null,false),
            
            );
    }
    private function chat()
    {
        $this->load_wordlist();
        $this->exms = explode(' ', $this->msg);
        $this->cword = count($this->exms);
        $this->mslg = strlen($this->msg);
        foreach ($this->wl as $key => $val) {
            if ($this->{'check'.$val[0]}($key, $val[2], $val[3], $val[4], $val[5], $val[6])) {
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
    }
        
        /**
        *		@param wordlist
        */
        private function check1($key, $wordcheck=false, $maxwords=null, $maxlength=null, $wordexception=null, $time=false)
        {
            /**
            *		Cek kelayakan :v
            */
            if (($maxlength!==null and $this->mslg>$maxlength) or ($maxwords!==null and $this->cword>$maxwords)) {
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
    private function gettimereply($replylist)
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
     private function fdate($string)
    {
        $pure = $string;
        $a = explode("#d(",$string);
        $a = explode(")",$a[1]);
        $b = explode("+",$a[0]);
        if (count($b)==1) {
            $b = explode("-",$a[0]);
            if (count($b)==1) {
                $out = $b[0];
                $tc = false;
            } else {
                $tc = true;
                $op = "-";
            }
        } else {
            $op = "+";
            $tc = true;
        }
        if ($tc) {
            $replacer = "#d(".$b[0].$op.$b[1].")";
            $c = strtotime(date("Y-m-d H:i:s").$op.$b[1],strtotime("Y-m-d H:i:s"));
            $b = $b[0];
        } else {
            $replacer = "#d(".$b[0].")";
            $c = strtotime(date("Y-m-d H:i:s"));
            $b = $b[0];
        }
        switch ($b) {
            case 'day': case 'days' : 
                $c = $this->hari[date("w",$c)];
                break;
            case 'jam' :
                $c = date("h:i:s",$c);
                break;
            case 'jam_sapa' :
                $c = "#".date("H");
                break;
        }
        $return = str_replace($replacer,$c,$pure);
        if (strpos($return,"#d(")!==false) {
            $return = $this->fdate($return);
        }
        return $return;
    }
}
