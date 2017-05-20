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
		private function load_wordlist()
		{
			/**
			*
			* hai,hay,hi,hy
			*		mode,answer,wordcheck,maxwords,maxlength,wordexception,time
			*/
			$this->wl = array(
				"hai,hay,hi,hy"=>array(
					1,array(
						"hai juga ^@",
						"hay juga ^@"
					),true,5,25,null,false
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
					$this->reply = $val[1][rand(0,count($val[1])-1)];
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
				if(count($a)>1){
					$notwr = true;
					foreach($a as $qw2){
						if(!in_array($qw2,$this->exms)){
							$notwr = false;
							break;
						}
					}
					if($notwr){
						return true;
					}
				}
				
				if($stop){
					return true;
				}
			}
		}
			
			return true;
		}
}