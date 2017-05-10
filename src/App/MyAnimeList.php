<?php
namespace App;

use Curl\CMCurl;

defined('data') or die("data not defined !");

/**
* @author Ammar F. <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept 2017
* @package tools
*/

class MyAnimeList
{
    private $hash;
    private $option;
    public function __construct($user, $pass)
    {
        is_dir(data.'/ani/myanimelist') or mkdir(data.'/ani/myanimelist');
        is_dir(data.'/ani/myanimelist/results') or mkdir(data.'/ani/myanimelist/results');
        is_dir(data.'/ani/myanimelist/image') or mkdir(data.'/ani/myanimelist/image');
        $this->option = array(CURLOPT_USERPWD=>"{$user}:{$pass}",CURLOPT_CONNECTTIMEOUT=>30);
    }
    public function search($query, $type=null)
    {
        $this->hash = md5($query.$type);
        $type = $type=='q_anime' ? 'anime' : 'manga';
        if (file_exists(data.'/ani/myanimelist/results/'.$this->hash)) {
            $result = file_get_contents(data.'/ani/myanimelist/results/'.$this->hash);
        } else {
            $ch = new CMCurl("https://myanimelist.net/api/{$type}/search.xml?q=".urlencode($query));
            $ch->set_optional($this->option);
            $result = $ch->execute();
            $ch->close();
            file_put_contents(data.'/ani/myanimelist/results/'.$this->hash, $result = json_encode(simplexml_load_string($result),128));
        }
        return $this->save_to_data(json_decode($result,true));
    }
    private function save_to_data($result)
    {
        $data = file_exists(data.'/ani/myanimelist/data.json') ? json_decode(file_get_contents(data.'/ani/myanimelist/data.json'),true) : array();
        foreach ($result['entry'] as $val) {
            $data['hash_table'][$this->hash][] = $val['id'];
        }
        file_put_contents(data.'/ani/myanimelist/data.json',json_encode($_r,128));
        return $result;
    }
}
