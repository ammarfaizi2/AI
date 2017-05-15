<?php
namespace System;

trait Chat
{
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
	*				
	*/
	private function check_word($input,$haystack,$identic=false,$timetr=false,$max_words=null,$word_excp=null)
	{

	}
	
	private function chat($msg)
	{
		foreach ($this->word_list as $key => $val) {
			
		}
	}
}