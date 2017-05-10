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
    private $history;
    public function __construct($user, $pass)
    {
        is_dir(data.'/ani/myanimelist') or mkdir(data.'/ani/myanimelist');
        is_dir(data.'/ani/myanimelist/results') or mkdir(data.'/ani/myanimelist/results');
        is_dir(data.'/ani/myanimelist/image') or mkdir(data.'/ani/myanimelist/image');
        $this->option = array(CURLOPT_USERPWD=>"{$user}:{$pass}",CURLOPT_CONNECTTIMEOUT=>30);
        $this->history = file_exists(data.'/ani/myanimelist/history.json') ? json_decode(data.'/ani/myanimelist/history.json',true) : array();
    }
    public function search($query, $type=null)
    {
        $type = $type==="q_anime" ? "anime" : "manga";
        $this->hash = md5($query.$type);
        if (file_exists(data.'/ani/myanimelist/results/'.$this->hash)) {
            $result = file_get_contents(data.'/ani/myanimelist/results/'.$this->hash);
        } else {
            $ch = new CMCurl("https://myanimelist.net/api/{$type}/search.xml?q=".urlencode($query));
            $ch->set_optional($this->option);
            $result = json_encode(simplexml_load_string($ch->execute()),128);
            $result=='false' or file_put_contents(data.'/ani/myanimelist/results/'.$this->hash, $result);
        }

        return $this->save_to_data(json_decode($result,true));
    }
    private function save_to_data($result)
    {
        $return = array();
        if (isset($result['entry'][0]['id'])) {
            $data = file_exists(data.'/ani/myanimelist/data.json') ? json_decode(file_get_contents(data.'/ani/myanimelist/data.json'),true) : array();
            $data = is_array($data) ? $data : array();
            foreach ($result['entry'] as $val) {
                $data['hash_table'][$this->hash][] = $val['id'];
                $return[$val['id']] = $val['title'];
            }
            if (!isset($data['hash_table'][$this->hash])) {                
                file_put_contents(data.'/ani/myanimelist/data.json', json_encode($data,128));
            }
        } else
        if (isset($result['entry']['id'])) {
            $data = file_exists(data.'/ani/myanimelist/data.json') ? json_decode(file_get_contents(data.'/ani/myanimelist/data.json'),true) : array();
            $data = is_array($data) ? $data : array();
                $data['hash_table'][$this->hash][] = $result['entry']['id'];
                $return[$result['entry']['id']] = $result['entry']['title'];
            if (!isset($data['hash_table'][$this->hash])) {
                file_put_contents(data.'/ani/myanimelist/data.json', json_encode($data,128));
            }
        }
        return $result===false ? null : $return;
    }
    public function get_info($id)
    {
        $data = json_decode(file_get_contents(data.'/ani/myanimelist/data.json'),true);
        $data = is_array($data) ? $data : array();
        if (!isset($data['hash_table'])) {
            return false;
        } else {
            return array_search($id, $data['hash_table']);
        }
    }
}
