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
		private function word_list()
		{
			/**
			*
			* hai,hay,hi,hy
			*		mode,answer,wordcheck
			*/
			$this->wl = array(
				'hai,hay,hi,hy'=>array(
					1,array(),true,
				),
			
			
			
			
			);
		}
		private function chat()
		{
			foreach($this->wl as $key => $val){
				if($r=$this->{'check'.$val[0]}($val[1],$val[2],$val[3])){
					
				}
			}
		}
		
		/**
		*		@param wordlist
		*/
		private function check1($wordlist,$wordcheck,$maxwords,$maxlength)
		{
			
		}
		private static function max_(int $n,int $max)
		{
			return $n>$max;
		}
}