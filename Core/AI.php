<?php 
date_default_timezone_set("Asia/Jakarta");
/**
* @author Ammar F. <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel_PHP_Concept (c) 2017
* @package Artificial Inteligence
*/
use tools\Google_Translate;
use tools\Google_Voice;
use tools\JadwalSholat;
use tools\Whois\Whois;
use tools\MyAnimeList;
use tools\SaferScript;
use tools\WhatAnime;
use tools\Brainly;
use tools\Writer;
use tools\Saklar;
use tools\TV;
class AI
{
    private $jam;
    private $sapa;
    private $jadwal;
    private $hari;
    public function __construct()
    {
        $this->gtime = strtotime(date("Y-m-d H:i:s")."+10minutes");
        $this->wordlist = array(
"hai,haii,hi,hii,hy,hyy,hay"=>array(
array(
"hai juga ^@",
),true,false,null,6,35,null),

"halo,hallo,allo,helo,hola,ello"=>array(
array(
"halo juga ^@",
),true,false,null,6,35,null),

"ohayo"=>array(
array(
"0-9,24"=>array("ohayou kang ^@, selamat beraktiftas"),
"10-11"=>array("selamat pagi menjelang siang ^@"),
"12-14"=>array("ini udah siang kang ^@ :v"),
"15-18"=>array("ini udah sore kang ^@"),
"19-23"=>array("ini udah malem kang ^@"),
),false,true,null,6,35,null),

"koniciwa,konnichiwa,konichiwa,konniciwa"=>array(
array(
"0-9,24"=>array("ini masih pagi kang ^@"),
"10-18"=>array("konnichiwa kang ^@, selamat beraktifitas"),
"19-23"=>array("ini udah malem kang ^@"),
),false,true,null,6,35,null),

"konbawa,konbanwa"=>array(
array(
"0-9,24"=>array("ini masih pagi kang ^@"),
"10-23"=>array("konbanwa kang ^@"),
),false,true,null,6,35,null),

"pagi"=>array(
array(
"0-9,24"=>array("selamat pagi kang  ^@, selamat beraktiftas"),
"10-11"=>array("selamat pagi menjelang siang ^@"),
"12-14"=>array("ini udah siang kang ^@ :v"),
"15-18"=>array("ini udah sore kang ^@"),
"19-23"=>array("ini udah malem kang ^@"),
),false,true,null,6,35,null),

"siang,ciang,siank"=>array(
array(
"0-9,24"=>array("ini masih pagi kang ^@"),
"10-15"=>array("selamat siang kang ^@, selamat beraktifitas"),
"16-18"=>array("ini udah sore kang ^@"),
"19-23"=>array("ini udah malem kang ^@"),
),true,true,null,6,35,null),

"sore"=>array(
array(
"0-9,24"=>array("ini masih pagi kang ^@"),
"10-14"=>array("ini masih siang kang ^@"),
"15-18"=>array("selamat sore kang ^@, selamat beristirahat"),
"19-23"=>array("ini udah malem kang ^@"),
),true,true,null,6,35,null),

"malam,malem"=>array(
array(
"0-9,24"=>array("ini masih pagi kang ^@"),
"10-14"=>array("ini masih siang kang ^@"),
"15-18"=>array("ini masih sore kang ^@"),
"19-23"=>array("selamat malam kang ^@, selamat beristirahat"),
),true,true,null,6,35,null),

"apa+kabar"=>array(
array(
"kabar baik disini",
"baik",
"sehat",
"jelek"
),false,false,null,6,35,null),

"jam+brp,jam+berapa,jm+brp,jm+berapa"=>array(
array(
"sekarang jam #d(jam) #d(jam_sapa)"
),true,false,null,15,45,null),

"what+time"=>array(
array(
"time #d(jam)"
),false,false,null,8,35,null),

"what+day"=>array(
array(
"today #d(day)"
),false,false,null,8,35,null),

"hari+apa+besok,besok+hari"=>array(
array(
"besok hari #d(day+1day)"
),false,false,null,10,45,null),

"hari+apa+kemarin,kemarin+hari"=>array(
array(
"kemarin hari #d(day-1day)"
),false,false,null,10,45,null),

"hari+apa"=>array(
array(
"sekarang hari #d(day)"
),false,false,null,10,45,null),

"makasih,terima+kasih,thank"=>array(
array(
"sama sama 😉",
"welcome 😃",
"senang mendengarnya 😉"
),false,false,null,8,45,null),

"arigato"=>array(
array(
"douita"
),false,false,null,7,45,null),

"es+teh,esteh"=>array(
array(
"es teh terasa segar ketika masuk ke mulut"
),false,false,null,5,30,null),

"pernah+ngoding"=>array(
array(
"oh sering"
),false,false,null,6,45,null),

"levvat,lewat"=>array(
array(
"dilarang lewat",
"mampir sekalian ga usah lewat"
),true,false,null,4,15,null),

"nyimak"=>array(
array(
"dilarang nyimak !"    
),false,false,null,6,45,null),

"ngoding+apaan,ngoding+apa"=>array(
array(
"coba tebak ngoding itu apa"
),false,false,null,7,45,null),

"ngoding,code,kode"=>array(
array(
"yuk ngoding",
"ngoding emang asik",
"ngoding eaa",
),true,false,null,25,100,null),

"kleng"=>array(
array(
"sokleng baso tengkleng"
),true,false,null,4,20,null),

"kucing"=>array(
array(
"wow kucing"
),true,false,null,8,55,null),

"lagi+apa"=>array(
array(
"lagi makan",
"bernafas",
"lagi mikir",
),false,false,null,4,30,null),

"mikir+apa"=>array(
array(
"mikir coding",
"mikir sawah"
),true,false,null,5,30,null),

"makan+apa"=>array(
array(
"makan nasi",
"makan tanah",
"makan kamu",
),false,false,null,5,50,null),

"dilarang"=>array(
array(
"ih ngelarang larang",
),true,false,null,5,25,null),

"makan"=>array(
array(
"makan yuk",
"makan apa?",
"udah makan?",
"pernah makan tanah?",
"pernah makan gamping?",
),false,false,null,5,50,null),

"oyasumi,oyasume"=>array(
array(
"oyasumi ^@",
"oyasuminasai",
),false,false,null,5,35,null),

"skripsi"=>array(
array(
"ciye skripsi :v",
"eaa skirpsi eaa :v",
),false,false,null,10,70,null),

"laravel"=>array(
array(
"kok kayak nama framework yak",
),true,false,null,10,45,null),

"ikut"=>array(
array(
"ikut kemana? ",
"dilarang ikut",
),true,false,null,5,30,null),

"proyek"=>array(
array(
"proyek nih asikk ",
),true,false,null,7,40,null),

"project"=>array(
array(
"project nih asikk ",
),true,false,null,7,40,null),

"sok+tau"=>array(
array(
"ciye sok tau",
"^@ ini memang sok tau"
),true,false,null,6,30,null),

"php"=>array(
array(
"ya, saya bisa php",
),true,false,null,5,30,null),

"zeeb,zeev"=>array(
array(
"zeeb ",
"zeeb :*"
),true,false,null,4,15,null),

"siapa"=>array(
array(
"siapa aja",
"siapapun"
),true,false,null,4,15,null),

"ntap"=>array(
array(
"mantapzz",
"ntapzz",
"mantap"
),false,false,null,5,25,null),

"hihi,haha,wkwk,xixi,xexe,wkaka,wkeke,wkoko"=>array(
array(
"dilarang ketawa",
),false,false,null,25,100,null),

"laper,lapar"=>array(
array(
"kalo laper ya makan :p",
"yuk makan :D"
),true,false,null,5,25,null),

":v,:'v,v':,v:,:\"v"=>array(
array(
"lu laper sampe mangap mangap gitu?",
"kenapa ^@, laper tha?"
),true,false,null,2,4,null),

"bot+kacang,bot+katjang"=>array(
array(
"nggk kacang kok, cuma nggk ngerti aja",
"nggk kacang, cuma bingung mo ngomong apa",
),false,false,null,6,25,null),

"bot"=>array(
array(
"apa kang ^@?"
),true,false,null,2,10,null),

);
        $this->jam = array('#01','#02','#03','#04','#05','#06','#07','#08','#09','#10','#11','#12','#13','#14','#15','#16','#17','#18','#19','#20','#21','#22','#23','#24','#00',);
        $this->sapa = array('dini hari','dini hari','dini hari','dini hari','pagi','pagi','pagi','pagi','pagi','menjelang siang','siang','siang','siang','siang','sore','sore','sore','sore','malam','malam','malam','malam','malam','dini hari','dini hari');
        $this->jadwal    = array(
"Senin"  =>"Senin\n\nUpacara\nBiologi\nKewirausahaan\nMatematika\nMatematika\nKimia\nKimia\nFisika\n\nPulang jam 16.00",
"Selasa" =>"Selasa\n\nGeografi\nGeografi\nEkonomi\nEkonomi\nMatematika\nSeni Budaya\nAgama\nKeiran (Japan)\n\nPulang jam 16.30",
"Rabu"   =>"Rabu\n\nB.Indonesia\nB.Indonesia\nB.Inggris\nB.Inggris\nBiologi\nBiologi\nAgama\nMatematika\nMatematika\n\nPulang jam 15.30",
"Kamis"  =>"Kamis\n\nOlahraga\nOlahraga\nOlahraga\nGeografi\nB.Indonesia\nPKN\nFisika\nFisika\n\nPulang jam 15.30",
"Jum'at" =>"Jum'at\n\nSenam\nMatematika\nMatematika\nKimia\nSejarah\nSejarah\n\nPulang jam 11.00",
"Sabtu"  =>"Sabtu\n\nPKN\nEkonomi\nB.Jawa\nPramuka(jarang)\nPramuka(jarang)\n\nPulang jam 11.00",
"Minggu" =>"Minggu\n\nNgisi kuliah kalsel\nNgisi kuliah Surabaya\nNgisi kuliah umum\nBebas"
            );
        $this->superuser = array("Ammar F","Ammar Faizi");
        $this->hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
        $this->root_command = array(
"@carik"=>1,
"off"=>2,
"on"=>2,
"shexec"=>1,
"shell_exec"=>1,
"eval"=>1,
);
        $this->command = array(
"ask"=>2,
"saklar"=>2,
"translate"=>2,
"ctranslate"=>3,
"whois"=>1,
"hitung"=>1,
"jadwal"=>1,
"lampu"=>2,
"tv"=>3,
"q_anime"=>1,
"q_manga"=>1,
"whatanime"=>1,
"sm1"=>1,
"sm0"=>1
);
    }
    private function ttreturn($key)
    {
        if (isset($this->wordlist[$key])) {
            foreach ($this->wordlist[$key] as $key => $val) {
                foreach ($val as $ky => $vl) {
                    $a=explode(",", $ky);
                    $tr=array();
                    foreach ($a as $b) {
                        $b=explode("-", $b);
                        if (count($b)==2) {
                            foreach (range($b[0], $b[1]) as $tmg) {
                                $tr[]=$tmg;
                            }
                        } else {
                            $tr[]=(int)$b[0];
                        }
                    }
                    if (in_array(date("H", $this->gtime), $tr)) {
                        return $vl[array_rand($vl)];
                        break;
                    }
                }
            }
        }
        return false;
    }
    private function word_check($needle, $haystack, $word_identical=false, $trreply=false, $timerange=null, $max_words=null, $max_length=null, $word_exception=null)
    {
        if (is_array($timerange)&&!in_array((int)date("H", $this->gtime), $timerange)) {
            return false;
        }
        if ($max_length
        !==null&&strlen($haystack)>(int)$max_length) {
            return false;
        }
        $stex=explode(" ", $haystack);
        if ($max_words!==null&&count($stex)>$max_words) {
            return false;
        }
        if (is_array($word_exception)) {
            foreach ($stex as $val1) {
                foreach ($word_exception as $val2) {
                    if ($val1==$val2) {
                        return false;
                        break;
                    }
                }
            }
        }
        $nd=explode(",", $needle);
        $sts=false;
        $wcheck=function ($qx, $hy, $wi=false) {
            $jl=0;
            if ($wi===true) {
                foreach ($hy as $t) {
                    foreach ($qx as $r) {
                        if ($t==$r) {
                            $jl++;
                        }
                    }
                }
            } else {
                foreach ($qx as $p0x) {
                    if (strpos($hy, $p0x)!==false) {
                        $jl++;
                    }
                }
            }
            return $jl>=count($qx)?true:false;
        };
        if ($word_identical===true) {
            foreach ($nd as $q) {
                $qx=explode("+", $q);
                foreach ($stex as $p) {
                    if ((count($qx)>1?$wcheck($qx, $stex, true):$p==$q)) {
                        $br=true;
                        $sts=true;
                        break;
                    }
                }
                if ($q==$haystack) {
                    $sts=true;
                    break;
                }
                if (isset($br)&&$br===true) {
                    break;
                }
            }
        } else {
            foreach ($nd as $q) {
                $qx=explode("+", $q);
                foreach ($stex as $p) {
                    if ((count($qx)>1?$wcheck($qx, $haystack, false):(strpos($haystack, $q)!==false))) {
                        $sts=true;
                        break;
                    }
                }
            }
        }
        if ($sts!==true) {
            return false;
        }
        return $trreply===true?$this->ttreturn($needle):$this->wordlist[$needle][0][array_rand($this->wordlist[$needle][0])];
    }
    public function prepare($string,$gcn=null)
    {
$this->gc = $gcn;    	
        $this->msg=strtolower($string);
        $this->_msg=$string;
        return $this;
    }
    
private function sm($actor){
$a = json_decode(Crayner_Machine::curl("http://yessrilanka.com/simisimi.php?msg=".urlencode($this->msg)),true);
file_put_contents("a.txt",json_encode($a));
if(isset($a['respSentence'])){
	if(strpos($a['respSentence'],"Saya belum paham")!==false){
	 $this->absmsg=false;
  $this->msg=null;
  $this->msgrt=null;
  $this->actor=null;
    return false;
	} else {
		$x = array("simi");
		$b = array("carik");
		$this->msg=null;
  $this->msgrt=str_ireplace($x,$b,urldecode($a['respSentence']));
  $this->actor=$actor;$this->absmsg=false;
		return true;
	}   	
    	
    	
    }}
public function writer()
{
	$aa = new Writer();
	$cc = (int)file_get_contents("c_materi");
	$cc = "materi_".$cc.".json";
	if($aa->open($cc,$this->gc)){
	$aa->write($actor,$this->msg,$this->gc);
	return $aa->save($cc);
}}
    public function execute($actor="",$stoper=false,$gcn=null)
    {
if(file_exists("writing")){
			$this->writer();
	}
    	if($stoper===true){return false;}
        $opmsg=explode(" ", $this->msg);
        $opmsg=strtolower($opmsg[0]);
        foreach ($this->root_command as $q => $val) {
            if ($opmsg==$q) {
                $this->absmsg=true;
                $this->msg=null;
                $this->msgrt=$this->spwcmd($q, $actor);
                $this->actor=$actor;
                return true;
                break;
            }
        }
        foreach ($this->command as $q => $z) {
if ($opmsg==$q) {
$this->absmsg=true;
$this->msg=null;
$this->msgrt=$this->spwcmd($q, $actor);
$this->actor=$actor;                   
return true;
break;
}
}
if (file_exists("bot_off")) {
$this->absmsg=false;
$this->msg=null;
$this->msgrt=null;
$this->actor=null;
return false;
}
        foreach ($this->wordlist as $key => $val) {
            if ($r=$this->word_check($key, $this->msg, (isset($val[1])?$val[1]:false), (isset($val[2])?$val[2]:false), (isset($val[3])?$val[3]:null), (isset($val[4])?$val[4]:null), (isset($val[5])?$val[5]:null))) {
                $this->absmsg=false;
                $this->msg=null;
                $this->msgrt=$r;
                $this->actor=$actor;
                return true;
                break;
            }
        }
if(file_exists("sm")){
return $this->sm($actor);
}
        $this->absmsg=false;
        $this->msg=null;
        $this->msgrt=null;
        $this->actor=null;
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
    public function fetch_reply()
    {
        if (!isset($this->absmsg)) {
            throw new Exception("Prepared statement not executed yet !", 3);
        }
        if ($this->absmsg==false) {
            $shact=explode(" ", $this->actor);
            $rt=str_replace("^@", $shact[0], $this->msgrt);
            $rt=str_replace("@", $this->actor, $rt);
            $rt=strpos($rt,"#d(")!==false?$this->fdate($rt):$rt;
            $rt=str_replace($this->jam, $this->sapa, $rt);
        } else {
            $rt=$this->msgrt;
        }
        return empty($rt)?false:$rt;
    }
}