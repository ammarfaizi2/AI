<?php
namespace App;

use Curl\CMCurl;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/
class WhatAnime
{
    public function __construct($in)
    {
        is_dir(data.'/ani') or mkdir(data.'/ani');
        is_dir(data.'/ani/wa') or mkdir(data.'/ani/wa');
        $hash = $in;
        if (file_exists(data.'/ani/wa/'.$hash)) {
            $this->result = json_decode(file_get_contents(data.'/ani/wa/'.$hash));
        }
        if (!isset($this->result)) {
            if ((substr($in, 0, 8)=="https://" or substr($in, 0, 7)=="http://")) {
                $st = new CMCurl($in);
                $dt = $st->execute();
                $st->close();
                file_put_contents('/ani/wa/'.md5($in), $dt);
                $in = base64_encode($dt);
            }
            $post = 'data=data%3Aimage%2Fjpeg%3Bbase64%2C'.urlencode($in);
            $ch = new CMCurl("https://whatanime.ga/search");
            $ch->set_header(array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8","X-Requested-With: XMLHttpRequest"));
            $ch->set_useragent("Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:46.0) Gecko/20100101 Firefox/46.0");
            $ch->set_optional(array(
                    CURLOPT_REFERER=>'https://whatanime.ga/'
                ));
            $this->result = $ch->execute();
            $ch->close();
        }
    }
}
