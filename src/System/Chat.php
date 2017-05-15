<?php
namespace System;

/**
*	@author Ammar Faizi <ammarfaizi2@gmail.com>
*/
trait Chat
{
	private $tmp_word;

	/**
	*	$similar percent
	*/
	private $similar = 50;

	private function word_list($text)
	{
		return array(
				"hai,hi,hy,hay" => array(
					array(
						"hai juga ^@",
						"hay juga ^@",
					),true,false,25,null),
				"apa+kabar,pa+kabar,apa+kbr,kabar" => array(
					array(

					),),



			);
	}
	
	/**
	*	Sorry penjelasan agak ngawur, harap maklum, gak bisa inggris :v
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
		$haystack = explode(",", $haystack);
		foreach ($haystack as $word) {
			
		}
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