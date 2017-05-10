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
    private $option;
    public function __construct($user, $pass)
    {
        is_dir(data.'/ani/myanimelist') or mkdir(data.'/ani/myanimelist');
        is_dir(data.'/ani/myanimelist/results') or mkdir(data.'/ani/myanimelist/results');
        $this->option = array(CURLOPT_USERPWD=>"{$user}:{$pass}",CURLOPT_CONNECTTIMEOUT=>30);
    }
    public function search($query, $type=null)
    {
        $type = $type=='q_anime' ? 'anime' : 'manga';
        if (file_exists(data.'/ani/myanimelist/results/'.md5($query.$type))) {
            $result = file_get_contents(data.'/ani/myanimelist/results/'.md5($query.$type));
        } else {
            $ch = new CMCurl("https://myanimelist.net/api/{$type}/search.xml?q=".urlencode($query));
            $ch->set_option($this->option);
            $result = $ch->execute();
            $ch->close();
            file_put_contents(data.'/ani/myanimelist/results/'.md5($query.$type), $result);
        }
        return simplexml_load_string($result);
    }
}
