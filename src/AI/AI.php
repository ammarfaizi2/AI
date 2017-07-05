<?php

namespace AI;

use AI\Abstraction\AIAbstraction;
use System\Exceptions\AIException;

class AI extends AIAbstraction
{	
	/**
	 * @var string
	 */
	private $input;

	/**
	 * @var string
	 */
	private $abs_input;

	/**
	 * @var string
	 */
	private $actor;

	/**
	 * Constructor.
	 * @throws System\Exception\AIException
	 */
	public function __construct()
	{
		if(! (defined("data") and defined("logs") and defined("storage"))) {
			throw new AIException("Needed constants not defined completely!", 1);
			die("Avoid catch AIException");
		}
		is_dir(data) or mkdir(data);		
		is_dir(logs) or mkdir(logs);
		is_dir(storage) or mkdir(storage);
		is_dir(data) or shell_exec("mkdir -p ".data);
		is_dir(logs) or shell_exec("mkdir -p ".logs);
		is_dir(storage) or shell_exec("mkdir -p ".storage);
	}

	/**
	 * @param string $input
	 * @param string $actor
	 */
	public function input($input, $actor)
	{
		$this->actor     = $actor;
		$this->input     = strtolower(trim($input));
		$this->abs_input = $input;
	}


}