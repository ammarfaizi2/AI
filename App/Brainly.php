<?php
namespace App;
defined('data') or die('Error : data not defined !');
use System\CM_Curl;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class Brainly
{
	private $text;
	private $limit;
	private $file;
	public function __construct()
	{
		is_dir(data.'/brainly') or mkdir(data.'/brainly');
		is_dir(data.'/brainly/data') or mkdir(data.'/brainly/data');
		is_dir(data.'/brainly/query') or mkdir(data.'/brainly/query');
	}
	
	public function prepare($text,$limit=100)
	{
		$this->text = urlencode($text);
		$this->limit = (int) $limit;
		$this->data = data.'/brainly/data.txt';
		$this->file = data.'/brainly/query/'.md5($text).'.txt';
	}
	
	public function execute()
	{
		if (file_exists($this->file)) {
			$a = json_decode(file_get_contents($this->file),true);
		}
			$ch = new CM_Curl('https://brainly.co.id/api/28/api_tasks/suggester?limit='.($this->limit).'&query='.$this->text);
			$a = json_decode($ch->execute(),true);
			file_put_contents($this->file, json_encode($a,128));
		}
		foreach ($a['data']['tasks']['items'] as $key => $val) {
			$que = trim(strip_tags(html_entity_decode($val['task']['content'],ENT_QUOTES,'UTF-8')));
			similar_text($que, $input, $percent);
			$sim[$key] = $percent;
		}
		$result = $a[array_search(max($sim),$sim)];
		if (isset($result['presence']['solved'][0]['id'])) {
			foreach ($result['responses'] as $val) {
				if ($val['user_id']==$result['presence']['solved'][0]['id']) {
					$ans = $val['content'];
					break;
				}
			}
		}
		if (!isset($ans)) {
			$return = false;
		} else {
			$return = true;
		}
		if ($return) {
			$this->result = array(trim(strip_tags(html_entity_decode($result['task']['content'],ENT_QUOTES,'UTF-8'))),trim(strip_tags(html_entity_decode($ans))));
		}
		return $return;
	}
	
	public function fetch_result()
	{
		return isset($this->result) ? $this->result : null;
	}
}