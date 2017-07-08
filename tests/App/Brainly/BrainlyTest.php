<?php

namespace tests\App\Brainly;

use AI\AI;
use PHPUnit\Framework\TestCase;

class BrainlyTest extends TestCase
{	
	public function testCache()
    {
    	$ai = new AI();
    	$ai->input("ask penemu lampu?", "PHPUnit S.");
        $this->assertTrue($ai->execute());
        $out1 = $ai->output();
        $ai = new AI();
        $ai->input("ask penemu lampu?", "PHPUnit S.");
        $this->assertTrue($ai->execute());
        $out2 = $ai->output();
        return [$out1, $out2];
    }

    public function testOnlineSearch()
    {
        $ai = new AI();
        $ai->input("ask aaaaaa ".rand().rand(), "PHPUnit S.");
        $this->assertTrue($ai->execute());        
        return $ai->output();
    }
}