<?php

namespace tests\App\Brainly;

use AI\AI;
use PHPUnit\Framework\TestCase;

class BrainlyTest extends TestCase
{	
	public function testCache()
    {
    	$ai = new AI();
    	$ai->input("ask siapakah penemu lampu?", "PHPUnit S.");
        $this->assertTrue($ai->execute());
        return $ai->output();
    }

    public function testOnlineSearch()
    {
        $ai = new AI();
        $ai->input("ask aaaaaa".rand().rand(), "PHPUnit S.");
        $this->assertTrue($ai->execute());
        return $ai->output();
    }
}