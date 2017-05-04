<?php
namespace App;

defined('data') or die('Error : data not defined !');
use System\CM_Curl;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class ChitChat
{
    private $ai_name;
    private $msg;
    private $cur;
    private $reply;
    public function __construct($ai_name='Carik')
    {
        is_dir(data.'/chitchat') or mkdir(data.'/chitchat');
        $this->ai_name    = strtolower($ai_name);
        $this->cur_file = data.'/chitchat/currentchat';
        $this->cur        = file_exists($this->cur_file) ? (int) file_get_contents($this->cur_file) : 1;
    }
    public function prepare($text)
    {
        $this->msg = urlencode(str_ireplace($this->ai_name, 'simi', $text));
        return $this;
    }
    public function execute()
    {
        $ch = new CM_Curl("http://www.simsimi.com/getRealtimeReq?uuid=j5AgOtBpNvzg8B5pRWYPnQhl2qktGLYbTC9LxXNCDyj&lc=en&ft=1&reqText=".($this->msg)."&status=W");
        $ch->set_header(array(
                'Cookie: _ga=GA1.2.1766465947.1489671555; uuid=j5AgOtBpNvzg8B5pRWYPnQhl2qktGLYbTC9LxXNCDyj; currentChatCnt='.(++$this->cur).'; _gid=GA1.2.1282686541.1493896281; _gat=1'
            ));
        file_put_contents($this->cur_file, $this->cur);
        $rt = json_decode($ch->execute(), true);
        if (is_array($rt)) {
            $this->reply = isset($rt['respSentence']) ? str_ireplace('sim'.$this->ai_name, $this->ai_name, str_ireplace('simi', $this->ai_name, $rt['respSentence'])) : null;
            return $this->reply===null ? false : true;
        } else {
            $this->reply = null;
            return false;
        }
    }
    public function fetch_reply()
    {
        return $this->reply===null ? false : $this->reply;
    }
}
