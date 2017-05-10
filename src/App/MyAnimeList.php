<?php
namespace App;

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
        $this->option = array(CURLOPT_USERPWD=>"{$user}:{$pass}",CURLOPT_CONNECTTIMEOUT=>30);
    }
    public function search($query, $type=null)
    {
        $ch = new CMCurl("https://myanimelist.net/api/".($type===null?"anime":$type)."/search.xml?q=".urlencode($query));
        $ch->set_option($this->option);
        $result = $ch->execute();
        $ch->close();
        $a = simplexml_load_string();
        return $a;
    }
}
