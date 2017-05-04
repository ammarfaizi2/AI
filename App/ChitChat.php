<?php
namespace App;
use System\CM_Curl;
defined('data') or die('data Constant is not defined !');

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class ChitChat
{
	const data = '/chitchat/';
	private $ai_name;
	private $msg;
	private $cur;
	public function __construct($ai_name='Carik')
	{
		$this->ai_name 	= strtolower($ai_name);
		$this->cur_file = data.self::data.'currentchat';
		$this->cur 		= file_exists($this->cur_file) ? (int) file_get_contents($this->cur_file) : 1;
	}
	public function prepare($text)
	{
		$this->msg = urlencode($text);
		return $this;
	}
	public function execute()
	{
		$ch = new CM_Curl("http://www.simsimi.com/getRealtimeReq?uuid=j5AgOtBpNvzg8B5pRWYPnQhl2qktGLYbTC9LxXNCDyj&lc=en&ft=1&reqText=".($this->msg)."&status=W");
		$ch->set_header(array(
				'Cookie: _ga=GA1.2.1766465947.1489671555; uuid=j5AgOtBpNvzg8B5pRWYPnQhl2qktGLYbTC9LxXNCDyj; currentChatCnt='.($this->cur).'; _gid=GA1.2.1282686541.1493896281; _gat=1'
			));
		$rt = json_decode($ch->execute(),true);
	}
}