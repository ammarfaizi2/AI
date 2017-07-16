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
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && (strpos(strtolower($out['text'][0]), "halo juga"))!==false;
        $this->assertTrue($cond);

        $ai = new AI();
        $ai->input("besok hari apa?", "PHPUnit S.");        
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && (strpos(strtolower($out['text'][0]), "besok hari"))!==false;
        $this->assertTrue($cond);

        $ai = new AI();
        $ai->input("jam berapa sekarang?", "PHPUnit S.");        
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && (strpos(strtolower($out['text'][0]), "sekarang jam"))!==false;
        $this->assertTrue($cond);

        $ai = new AI();
        $ai->input("kemarin hari apa?", "PHPUnit S.");        
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && (strpos(strtolower($out['text'][0]), "kemarin hari"))!==false;
        $this->assertTrue($cond);

        $ai = new AI();
        $ai->input("apa kabar", "PHPUnit S.");        
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && (strpos(strtolower($out['text'][0]), "kabar"))!==false;
        $this->assertTrue($cond);

        $ai = new AI();
        $ai->input("tanggal berapa", "PHPUnit S.");        
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && (strpos(strtolower($out['text'][0]), "tanggal"))!==false;
        $this->assertTrue($cond);
    }

    public function testNoReply()
    {
        $ai = new AI();
        $ai->input("qqqqq", "PHPUnit S.");
        $this->assertTrue(!$ai->execute());

        $ai = new AI();
        $ai->input("akdsmfksmadlkfmaslkdfmiweomqimqwer", "PHPUnit S.");
        $this->assertTrue(!$ai->execute());
    }
}
