<?php

namespace AI\AppConnector;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

class Brainly
{
	/**
	 * @param string
	 */
	public function __construct($q)
	{
		$st = new \App\Brainly\Brainly($q);
		$st->execute();
		$st = $st->get_result();
		var_dump($st);
		die;
	}
}
