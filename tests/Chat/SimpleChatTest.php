<?php

namespace tests\Chat;

use AI\AI;
use PHPUnit\Framework\TestCase;

class SimpleChatTest extends TestCase
{
    public function testWithReply()
    {
        $ai = new AI();
        $ai->input("halo", "PHPUnit S.");
        $this->assertTrue($ai->execute());
        $out = $ai->output();
        return $out;
    }

    public function testNoReply()
    {
        $ai = new AI();
        $ai->input("qqqqq", "PHPUnit S.");
        $this->assertTrue(!$ai->execute());
        return $ai->output();
    }

    public function testTanggal()
    {
        $ai = new AI();
        $ai->input("qqqqq", "PHPUnit S.");
        $this->assertTrue(!$ai->execute());
        return $ai->output();
    }
}
