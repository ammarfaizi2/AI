<?php

namespace AI;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @license MIT
 * @version 0.0.1
 */
final class AI
{	

	const VERSION = "0.0.1";

	/**
	 * @var string
	 */
	private $sentence;

	/**
	 * @var string
	 */
	private $output;

	/**
	 * Constructor.
	 *
	 * @param string $sentence
	 */
	public function __construct($sentence)
	{
		$this->sentence = $sentence;
		if (defined("AI_DIR")) {
			
		}
	}

	/**
	 * @return bool
	 */
	public function execute()
	{

	}

	/**
	 * @return string
	 */
	public function output()
	{
		return $this->output;
	}
}