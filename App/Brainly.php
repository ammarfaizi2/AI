<?php
namespace App;
use System\CM_Curl;

/**
* @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
* @license RedAngel PHP Concept
*/

class ClassName extends AnotherClass
{
	
	public function __construct()
	{

	}
	
	public function prepare($text,$limit=100)
	{
		$this->text = urlencode($text);
	}
	
	public function execute()
	{
		$ch = new CM_Curl('https://brainly.co.id/api/28/api_tasks/suggester?limit='.($this->limit).'&query='.$this->text);
		$a = json_decode($a,true);
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
			$result = array(trim(strip_tags(html_entity_decode($result['task']['content'],ENT_QUOTES,'UTF-8'))),trim(strip_tags(html_entity_decode($ans))));
		}
		return $return;
	}
	
	public function fetch_result()
	{

	}
}