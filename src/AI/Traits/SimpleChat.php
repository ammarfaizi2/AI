<?php

namespace AI\Traits;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

trait SimpleChat
{
	private function simple_chat()
	{
		foreach (\AI\CY\ChatST::$wordlist as $k => $v) {
			if ($this->compare($this->input, $k, $v['t'][0], $v['t'][1])) {
				# code...
			}
		}
	}

	/**
	 * @param string $input
	 * @param string $key
	 * @param bool	 $word_match
	 * @param int	 $max_length	Max length to response.
	 * @param int	 $max_words		Max words to response.
	 * @param bool   $time_reply	
	 */
	private function compare($input, $key, $word_match = false, $max_length = null, $max_words = null, $time_reply = false)
	{

	}
}