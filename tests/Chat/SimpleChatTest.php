<?php

namespace tests\Chat;

use AI\AI;
use PHPUnit\Framework\TestCase;

class SimpleChatTest extends TestCase
{
	public function test1()
	{
		$ai = new AI();
		$ai->input("halo", "PHPUnit S.");
		$this->assertTrue($ai->execute());
		$out = $ai->output();
		return $out;
	}
}