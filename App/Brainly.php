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
	private $data;
	private $hash;
	private $file;
	public function __construct()
	{
		is_dir(data.'/brainly') or mkdir(data.'/brainly');
		is_dir(data.'/brainly/data') or mkdir(data.'/brainly/data');
		is_dir(data.'/brainly/query') or mkdir(data.'/brainly/query');
	}
	
	public function prepare($text,$limit=100)
	{
		$this->text = $text;
		$this->limit = (int) $limit;
		$this->data = file_exists(data.'/brainly/data.txt') ? json_decode(data.'/brainly/data.txt') : array();
		$this->data = $this->data===null ? array() : $this->data;
		$this->hash = md5($text);
		$this->file = data.'/brainly/query/'.($this->hash).'.txt';
		in_array($this->hash, $this->data) or $this->data[] = $this->hash;
	}
	
	public function execute()
	{
		if (file_exists($this->file)) {
			$a = json_decode(file_get_contents($this->file),true);
		} else {
			$ch = new CM_Curl('https://brainly.co.id/api/28/api_tasks/suggester?limit='.($this->limit).'&query='.urlencode($this->text));
			$a = json_decode($ch->execute(),true);
			file_put_contents($this->file, json_encode($a,128));
		}
		foreach ($a['data']['tasks']['items'] as $key => $val) {
			$que = trim(strip_tags(html_entity_decode($val['task']['content'],ENT_QUOTES,'UTF-8')));
			similar_text($que, $this->text, $percent);
			$sim[$key] = $percent;
		}
		$result = $a['data']['tasks']['items'][array_search(max($sim),$sim)];
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
		file_put_contents(data.'/brainly/data.txt', json_encode($this->data,128));
		unset($this->data);
		return $return;
	}
	
	public function fetch_result()
	{
		return isset($this->result) ? $this->result : null;
	}
}