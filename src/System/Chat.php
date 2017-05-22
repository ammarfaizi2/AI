<?php
namespace System;

/**
*	@author Ammar Faizi <ammarfaizi2@gmail.com>
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
			/**
			*
			* hai,hay,hi,hy
			*		mode,answer,wordcheck,maxwords,maxlength,wordexception,time
			*/
			$this->wl = array(
				":v,:'v,:\"v,v:,v':,v\":"=>array(
					1,array(
						"lu laper sampe mangap mangap gitu? 😎",
						"kenapa ^@, laper banget tha?",
						"dilarang mangap 😒",
						"mangap mangap itu gak sehat kang ^@"
					),true,2,8,null,false
				),
				/**/
				"laper,lapar,lavar"=>array(
					1,array(
						"0-3"=>array(
							"segera sahur kang ^@",
							"sahur dulu kang ^@ 😊"
						),
						"4-15"=>array(
							"sabar kang ^@, belum waktunya berbuka 😇",
							"yang sabar ya kang ^@, kita tunggu sampai waktunya berbuka..."
						),
						"16-17"=>array(
							"sabar kang ^@, bentar lagi magrib kok 😏",
							"sabar aja ya kang ^@, sebentar lagi udah magrib 😋😏"
						),
						"18-24"=>array(
							"kalo laper ya makan 😜",
							"makan gamping 😋",
							"makan aeeh :v"
						)
					),false,6,45,null,true
				),
				"hai,hay,hi,hy"=>array(
					1,array(
						"hai juga ^@",
						"hay juga ^@"
					),true,5,25,null,false
				),
				/**/
				"apa+kabar"=>array(
					1,array(
						"kabar baik disini...",
						"kabar baik, ^@ apa kabar?"
					),false,8,35,null,false
				),
				/**/
				"jam+ber,jam+brp"=>array(
					1,array(
						("sekarang jam ".date("h:i:s"))	
					),false,8,35,null,false
				),
				/**/
				"pagi"=>array(
				1,array(
					"1-10"=>array(
							"selamat pagi kang ^@, selamat beraktifitas..."
					),
					"11-14"=>array(
						"ini udah siang kang ^@"
					),
					"15-18"=>array(
						"ini udah sore kang ^@"
					),
					"19-23,0"=>array(
						"ini udah malem kang ^@"
					),
				),false,8,35,null,true
				),
			
			
			
			);
		}
		private function chat()
		{
			$this->load_wordlist();
			$this->exms = explode(' ',$this->msg);
			$this->cword = count($this->exms);
			$this->mslg = strlen($this->msg);
			foreach($this->wl as $key => $val){
				if($this->{'check'.$val[0]}($key,$val[2],$val[3],$val[4],$val[5],$val[6])){
					if($this->timereply){
						if($this->gettimereply($val[1])){
						$val[1] = $this->timereply;
						} else {
							return false;
						}
					}
					$act = explode(" ",$this->actor);
					$this->reply = str_replace("@",$this->actor,str_replace("^@",$act[0],$val[1][rand(0,count($val[1])-1)]));
					return true;
				}
			}
		}
		
		/**
		*		@param wordlist
		*/
		private function check1($key,$wordcheck=false,$maxwords=null,$maxlength=null,$wordexception=null,$time=false)
		{
			/**
			*		Cek kelayakan :v
			*/
			if(($maxlength!==null and $this->mslg>$maxlength) or ($maxwords!==null and $this->cword>$maxwords)){
				return false;
			}
			if(is_array($wordexception)){
				foreach($wordexception as $qw){
					if(in_array($qw,$this->exms)){
						return false;
					}
				}
			}
			
		$ex = explode(',',$key);
		if($wordcheck){
			$stop = false;
			foreach($ex as $qw){
				$a = explode('+',$qw);
				$notwr = true;
				foreach($a as $qw2){
					if(!in_array($qw2,$this->exms)){
						$notwr = false;
						break;
					}
				}
			if($notwr){
				if($time){
					$this->timereply = true;
				}
				return true;
			}
		}
	} else {
		$stop = false;
		foreach($ex as $qw){
				$a = explode('+',$qw);
				$notwr = true;
				foreach($a as $qw2){
					if(strpos($this->msg,$qw2)===false){
						$notwr = false;
						break;
					}
				}
			if($notwr){
				if($time){
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
		foreach($replylist as $time => $replist){
			$tr = array();
			$a = explode(",",$time);
			foreach($a as $b){
				$c = explode("-",$b);
				if(count($c)==1){
					$tr[] = $c[0];
				} else {
					$tr = array_merge($tr,range($c[0],$c[1]));
				}
				if(in_array((int)date("H"),$tr)){
					$this->timereply = $replist;
					return true;
				}
			}
		}
		return false;
	}
}