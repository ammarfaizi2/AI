<?php
namespace App;

defined('data') or die("data not defined !");

use Curl\CMCurl;
use AI\AIFoundation;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

class WhatAnime extends AIFoundation
{
    public function __construct($in)
    {
        is_dir(data.'/ani') or mkdir(data.'/ani');
        is_dir(data.'/ani/wa') or mkdir(data.'/ani/wa');
        is_dir(data.'/ani/wa/results') or mkdir(data.'/ani/wa/results');
        is_dir(data.'/ani/wa/history') or mkdir(data.'/ani/wa/history');
        $this->hash = md5($in);
        $this->input = $in;
    }
    public function execute()
    {
        if (file_exists(data.'/ani/wa/results/'.$this->hash)) {
            $this->result = file_get_contents(data.'/ani/wa/results/'.$this->hash);
            return true;
        } else {
            if (file_exists(data.'/ani/wa/history/'.$this->hash)) {
                $this->file = base64_encode(file_get_contents(data.'/ani/wa/history/'.$this->hash));
            } else {
                $ch = new CMCurl($this->input);
                $ch->set_useragent();
                $file = $ch->execute();
                file_put_contents(data.'/ani/wa/history/'.$this->hash, $file);
                $this->file = base64_encode($file);
            }
        }
        $ch = new CMCurl("https://whatanime.ga/search");
        $ch->set_useragent();
        $ch->set_optional(array(
                    CURLOPT_REFERER=>"https://whatanime.ga/?url=".urlencode($this->input)
                ));
        $ch->set_header(array(
            "X-Requested-With: XMLHttpRequest",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"
        ));
        $ch->set_post("data=data%3Aimage%2Fjpeg%3Bbase64%2C".urlencode($this->file));
        $this->result = $ch->execute();
        file_put_contents(data.'/ani/wa/results/'.$this->hash, json_encode(json_decode($this->result), 128));
        $ch->close();
        return true;
    }

    /**
    *   @return array
    */
    public function fetch_result($array=true)
    {
        return json_decode($this->result, $array);
    }
}
