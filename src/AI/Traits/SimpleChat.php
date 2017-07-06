<?php

namespace AI\Traits;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

trait SimpleChat
{
    private function simple_chat()
    {
        if (is_array($this->lang)) {
            foreach ($this->lang as $lang) {
                if ($this->chat_st("\\AI\\CY\\ChatST\\{$lang}")) {
                    return true;
                }
            }
        } else {
            return $this->chat_st("\\AI\\CY\\ChatST\\{$this->lang}");
        }
        return false;
    }

    /**
     * @see \AI\CY\ChatST
     * @param string $cst
     */
    private function chat_st($cst)
    {
        foreach ($cst::$wordlist as $keys => $v) {
            if ($st = $this->compare($this->input, $keys, $v['t'][0], $v['t'][1], $v['t'][2], $v['t'][3])) {
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
     * @param bool   $word_match
     * @param int    $max_length Max length to response.
     * @param int    $max_words  Max words to response.
     * @param bool   $time_reply
     */
    private function compare($input, $keys, $word_match = false, $max_length = null, $max_words = null, $time_reply = false)
    {
        $input_length = strlen($input);
        if ($max_length != null and $input_length > $max_length) {
            return false;
        }
        $exploded_input = explode(" ", $input);
        if ($max_words != null and count($exploded_input) > $max_words) {
            return false;
        }
        $exploded_keys = explode(",", $keys);
        if ($word_match) {
            $main_flag = false;
            foreach ($exploded_keys as $ke) {
                $ck = explode("+", $ke);
                $sub_flag = true;
                foreach ($ck as $ck) {
                    if (!in_array($ck, $exploded_input)) {
                        $sub_flag = false;
                        break;
                    }
                }
                if ($sub_flag) {
                    $main_flag = true;
                    break;
                }
            }
            return $main_flag ? ($time_reply ? 2 : 1) : 0;
        }
    }

    /**
     * @return string
     */
    private function response_fixer($resp)
    {
        return str_replace("@", $this->actor, str_replace("^@", $this->actor_call, $resp));
    }
}
