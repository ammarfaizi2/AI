<?php

namespace AI;

use AI\Abstraction\AIAbstraction;

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
	 */
	public function __construct()
	{
		is_dir(data) or mkdir(data);
		is_dir(storage) or mkdir(storage);
		is_dir(logs) or mkdir(logs);
		is_dir(data) or shell_exec("mkdir -p ".data);
		is_dir(storage) or shell_exec("mkdir -p ".storage);
		is_dir(logs) or shell_exec("mkdir -p ".logs);
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