<?php

namespace AI\Abstraction;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

abstract class AIAbstraction
{
	/**
	 * Constructor.
	 */
	abstract public function __construct();

	/**
	 * Input
	 */
	abstract public function input($input, $actor = "");
}
