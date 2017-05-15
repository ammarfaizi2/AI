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

	/**
	*	@return (array) word_list
	*/
	private function word_list()
	{
		return array(
				"hai,hi,hy,hay" => array(
					array(
						"hai juga ^@",
						"hay juga ^@",
					),true,false,25,null),
				"apa+kabar,pa+kabar,apa+kbr,kabar" => array(
					array(
						"kabar baik disini...",
						"alhamdulilah sehat...",
						"baik baik saja...",
					),true,false,25,null),
			);
	}
	
	/**
	*	Sorry penjelasan agak ngawur, harap maklum, gak bisa inggris :v
	*	@param	input 		= input pesan (string)
	*	@param	haystack	= wordlist (string)		
	*	@param	identic		= word identical (bool)
	*	@param	timetr		= time to reply (bool)
	*	@param	max_words	= input max words to reply (int)
	*	@param	word_excp	= word exception (string)
	*	@return bool
	*/
	private function check($input,$haystack,$identic=false,$timetr=false,$max_words=null,$word_excp=null)
	{
		$intoword	= explode(" ",$input);
		$haystack	= explode(",", $haystack);
		$countin	= count($intoword);

		/**
		* Check max words
		*/
		if (($countin>(int)$max_words)) {
			return false;
		}

		/**
		*	Check word_excp
		*/
		if ($word_excp!==null) {
			$word_excp = explode(",", $word_excp);
			foreach ($intoword as $word) {
				if(in_array(trim($word), $word_excp)){
					return false;
				}
			}
		}

		if ($timetr===true) {
			/**
			*	Reply with time
			*/
		} else {
			foreach ($haystack as $word) {
				if ($input==$word) {
					$this->reply = $this->tmp_word[rand(0,count($this->tmp_word)-1)];
					return true;
				}
			}
		}
	}
	
	/**
	*	@param 	string msg
	*	@return	bool
	*/	
	private function chat($msg)
	{
		foreach ($this->word_list() as $key => $val) {
			$this->tmp_word = $val[0];
			if($r=$this->check($msg,$key,$val[1],$val[2],$val[3],$val[4])){
				$actor = explode(" ", $this->actor,2);
				$this->reply = str_replace("^@", $actor[0], $this->reply);
				$this->reply = str_replace("@", $this->actor, $this->reply);
				return true;
			}		
		}
		return false;
	}
}