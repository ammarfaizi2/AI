<?php
namespace App;

defined('data') or die("data not defined !");

use Curl\CMCurl;
use AI\AIFoundation;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

class MyAnimeList extends AIFoundation
{
    private $hash;
    private $option;
    private $history;
    public function __construct($user, $pass)
    {
        is_dir(data.'/ani') or mkdir(data.'/ani');
        is_dir(data.'/ani/myanimelist') or mkdir(data.'/ani/myanimelist');
        is_dir(data.'/ani/myanimelist/results') or mkdir(data.'/ani/myanimelist/results');
        is_dir(data.'/ani/myanimelist/image') or mkdir(data.'/ani/myanimelist/image');
        $this->option = array(CURLOPT_USERPWD=>"{$user}:{$pass}",CURLOPT_CONNECTTIMEOUT=>30);
        $this->history = file_exists(data.'/ani/myanimelist/history.json') ? json_decode(data.'/ani/myanimelist/history.json', true) : array();
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
            $result = json_encode(simplexml_load_string($ch->execute()), 128);
            $result=='false' or file_put_contents(data.'/ani/myanimelist/results/'.$this->hash, $result);
        }

        return $this->save_to_data(json_decode($result, true));
    }
    private function save_to_data($result)
    {
        if ($result===false) {
            return false;
        }
        $return = array();
        if (isset($result['entry'][0]['id'])) {
            $_data = file_exists(data.'/ani/myanimelist/data.json') ? json_decode(file_get_contents(data.'/ani/myanimelist/data.json'), true) : array();
            $data = is_array($_data) ? $_data : array();
            foreach ($result['entry'] as $val) {
                $data['hash_table'][$this->hash][] = $val['id'];
                $return[$val['id']] = $val['title'];
            }
            if (!isset($_data['hash_table'][$this->hash])) {
                file_put_contents(data.'/ani/myanimelist/data.json', json_encode($data, 128));
            }
        } elseif (isset($result['entry']['id'])) {
            $_data = file_exists(data.'/ani/myanimelist/data.json') ? json_decode(file_get_contents(data.'/ani/myanimelist/data.json'), true) : array();
            $data = is_array($_data) ? $_data : array();
            $data['hash_table'][$this->hash][] = $result['entry']['id'];
            $return[$result['entry']['id']] = $result['entry']['title'];
            if (!isset($_data['hash_table'][$this->hash])) {
                file_put_contents(data.'/ani/myanimelist/data.json', json_encode($data, 128));
            }
        }
        $rt = '';
        foreach ($return as $key => $value) {
            $rt.= $key.' : '.$value."\n";
        }
        return $rt;
    }
    public function get_info($id)
    {
        $data = json_decode(file_get_contents(data.'/ani/myanimelist/data.json'), true);
        $data = is_array($data) ? $data : array();
        if (!isset($data['hash_table'])) {
            return false;
        } else {
            foreach ($data['hash_table'] as $key => $value) {
                if (array_search($id, $value)!==false) {
                    $file = $key;
                    break;
                }
            }
            if (!isset($file)) {
                return false;
            }
            $info = json_decode(file_get_contents(data.'/ani/myanimelist/results/'.$file), true);
            if (isset($info['entry'][0]['id'])) {
                foreach ($info['entry'] as $key => $val) {
                    if ($val['id']==$id) {
                        $val = $val;
                        break;
                    }
                }
            } else {
                $val = $info['entry'];
            }
            $return = '';
            $image = $val['image'];
            unset($val['image']);
            foreach ($val as $key => $value) {
                $return .= ucwords(str_replace("_", " ", $key))." : ".str_replace("<br />", "\n", html_entity_decode($value, ENT_QUOTES | ENT_IGNORE, 'UTF-8'))."\n";
            }
        }
        return isset($return) ? array($image,$return) : false;
    }

    public function execute()
    {
    }
}
