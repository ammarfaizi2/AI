<?php

namespace AI\Contracts;

interface StringManagement
{	
	/** 
	 * @param string $string
	 * @return array
	 */
	public function getArgv(string $string);
}