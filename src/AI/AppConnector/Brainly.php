<?php

namespace AI\AppConnector;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

class Brainly
{
	/**
	 * @var array 
	 */
	private $return = array();

	/**
	 * @param string
	 */
	public function __construct($q)
	{
		$st = new \App\Brainly\Brainly($q);
		$st->execute();
		$st = $st->get_result();
		$st = $st['data']['tasks']['items'];
		$sim = $lev = [];
		foreach ($st as $key => $val) {
			$val = html_entity_decode(strip_tags($val['task']['content']), ENT_QUOTES, 'UTF-8');
			$lev[$key] = levenshtein($val, $q);
			similar_text($val, $q, $n);
			$sim[$key] = $n;
		}
		$fx = function($str)
		{
			return html_entity_decode(str_replace("<br />", "\n", $str), ENT_QUOTES, 'UTF-8');
		};
		if (min($lev) <= 5) {
			$key = array_search(min($lev), $st);
			$rt = array($fx($st[$key]['task']['content']), $fx($st[$key]['responses'][0]['content']));
		} else {
			$key = array_search(max($sim), $st);
			$rt = array($fx($st[$key]['task']['content']), $fx($st[$key]['responses'][0]['content']));
		}
		$this->return = $rt;
	}

	/**
	 * @return array
	 */
	public function get_result()
	{
		return $this->return;
	}
}
