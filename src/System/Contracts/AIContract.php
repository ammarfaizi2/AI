<?php

namespace System\Contracts;

interface AIContract
{
	/**
	 * Constructor.
	 */
	public function __construct();

	/**
	 * Input
	 */
	public function input($input, $actor = "");

	/**
	 * Set timezone
	 */
	public function set_timezone($timezone);
}
