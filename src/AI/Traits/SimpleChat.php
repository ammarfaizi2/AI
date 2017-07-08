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
                if ($this->chat_st("\\AI\\Lang\\ChatST\\{$lang}")) {
                    return true;
                }
            }
        } else {
            return $this->chat_st("\\AI\\Lang\\ChatST\\{$this->lang}");
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
            if ($st = $this->compare($this->input, $keys, $v['t'][0], $v['t'][1], $v['t'][2], $v['t'][3], (isset($v['word_exception']) ? $v['word_exception'] : []))) {
                if ($st == 1) {
                    $this->output = [
                            "text" => [
                                $this->response_fixer($v['r'][array_rand($v['r'])])
                            ]
                        ];
                } else {
                    if ($resp =  $this->time_based_response($v['r'])) {
                        $this->output = [
                            "text" => [
                                $this->response_fixer($resp)
                            ]
                        ];
                    }
                }
                break;
            }
        }
        return (bool) count($this->output);
    }

    /**
     * @param string $input
     * @param string $key
     * @param bool   $word_match
     * @param int    $max_length     Max length to response.
     * @param int    $max_words      Max words to response.
     * @param bool   $time_reply
     * @param array  $word_exception
     */
    private function compare($input, $keys, $word_match = false, $max_length = null, $max_words = null, $time_reply = false, $word_exception = [])
    {
        $input_length = strlen($input);
        if ($max_length != null and $input_length > $max_length) {
            return false;
        }
        $exploded_input = explode(" ", $input);
        foreach ($word_exception as $word) {
            if (in_array($word, $exploded_input)) {
                return false;
            }
        }
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
                    $ck = preg_replace("#[^a-zA-Z]#", "", $ck);
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
        } else {
            $main_flag = false;
            foreach ($exploded_keys as $ke) {
                $ck = explode("+", $ke);
                $sub_flag = true;
                foreach ($ck as $ck) {
                    if (strpos($input, $ck) === false) {
                        $sub_flag = false;
                        break;
                    }
                }
                if ($sub_flag) {
                    $main_flag = true;
                    break;
                }
            }
            return $main_flag ? ($time_reply ? 2 : 1) : false;
        }
    }

    /**
     * @return string
     */
    private function response_fixer($resp)
    {
        return $this->fdate(str_replace("@", $this->actor, str_replace("^@", $this->actor_call, $resp)));
    }

    /**
     * @param array
     */
    private function time_based_response($response_list)
    {
        foreach ($response_list as $key => $val) {
            $this_time = [];
            $a = explode(",", $key);
            foreach ($a as $jam) {
                $b = explode("-", $jam);
                if (count($b) == 2) {
                    $this_time = array_merge($this_time, range((int) $b[0], (int) $b[1]));
                } else {
                    $this_time[] = (int) $b[0];
                }
            }
            if (in_array((int) date("H", $this->gentime()), $this_time)) {
                return $val[array_rand($val)];
            }
        }
        return false;
    }

    /**
     * @param string
     */
    private function fdate($string)
    {
        $pure = $string;
        $a = explode("#d(", $string);
        if (!isset($a[1])) {
            return $string;
        }
        $a = explode(")", $a[1]);
        $b = explode("+", $a[0]);
        if (count($b)==1) {
            $b = explode("-", $a[0]);
            (count($b)==1) and ($out = $b[0] xor $tc = false) or ($tc = true xor $op = "-");
        } else {
            ($op = "+" xor $tc = true);
        }
        if ($tc) {
            $replacer = "#d(".$b[0].$op.$b[1].")";
            $c = strtotime(date("Y-m-d H:i:s").$op.$b[1], strtotime("Y-m-d H:i:s"));
            $b = $b[0];
        } else {
            $replacer = "#d(".$b[0].")";
            $c = strtotime(date("Y-m-d H:i:s"));
            $b = $b[0];
        }
        $dcls = "\\AI\\Lang\\DateST\\".$this->lang;
        switch ($b) {

            /**
             *  Untuk hari.
             */
        case 'day': case 'days':
                $c = $dcls::$day[date("w", $c)];
            break;

            /**
             *  Untuk jam.
             */
        case 'jam':
            $c = date("h:i:s", $c);
            break;

            /**
             *  Untuk bulan.
             */
        case 'month':
            $c = $dcls::$month[(int)date("m", $c)];
            break;

            /**
             *  Untuk tanggal.
             */
        case 'date_c':
            $c = $this->hari[date("w", $c)].", ".date("d", $c)." ".($this->bulan[date("m", $c)])." ".date("Y", $c);
            break;

            /**
             *  Tidak dikenal.
             */
        default:
            $c = "unknown_param({$c})";
            break;
        }
        $return = str_replace($replacer, $c, $pure);
        !(strpos($return, "#d(")===false) and $return = $this->fdate($return);
        return $return;
    }
}
