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
			if ($st = $this->compare($this->input, $k, $v['t'][0], $v['t'][1], $v['t'][2], $v['t'][3])) {
				if ($st == 1) {
					$this->output = [
							"text" => [
								$this->response_fixer($v['r'][array_rand($v['r'])])
							]
						];
				} else {

				}
			}
		}
		return (bool) count($this->output);
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
		return 1;
	}

	/**
	 *
	 */
	private function response_fixer()
	{
		
	}
}