<?php

namespace AI;

use System\Hub\Singleton;
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