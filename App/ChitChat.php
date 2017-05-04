<?php
namespace App;
use System\CM_Curl;
/**
* 
*/
class ChitChat
{
	private $ai_name;
	public function __construct($ai_name='Carik')
	{
		$this->ai_name 	= strtolower($ai_name);
	}
	public function prepare($text)
	{
		$ch = new CM_Curl();
	}
}