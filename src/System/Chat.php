<?php
namespace System;

trait Chat
{
	private $tmp_word;
	private function word_list($text)
	{
		return array(
				"hai,hi,hy,hay" => array(
					array(
						"hai juga ^@",
						"hay juga ^@",
					),true,true)
				,



			);
	}
	
	/**
	*	@param	input 		= input pesan (string)
	*	@param	haystack	= wordlist (string)		
	*	@param	identic		= word identical (bool)
	*	@param	timetr		= time to reply (bool)
	*	@param	max_words	= input max words to reply (int)
	*	@param	word_excp	= word exception (array) (string) `numeric array`
	*	@return mixed null|(string)
	*/
	private function check($input,$haystack,$identic=false,$timetr=false,$max_words=null,$word_excp=null)
	{

	}
	
	private function chat($msg)
	{
		foreach ($this->word_list as $key => $val) {
			$this->tmp_word = $val[0];
			if($r=$this->check($msg,$key,$val[1],$val[2],$val[3],$val[4])){
				return $r;
			}		
		}
	}
}