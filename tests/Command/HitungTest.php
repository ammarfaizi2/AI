<?php

namespace tests\Command;

use AI\AI;
use PHPUnit\Framework\TestCase;

class HitungTest extends TestCase
{
    public function testHitungBasic()
    {
        $ai = new AI();
        $ai->input("hitung 1+1");
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && ($out['text'][0]==2);
        $this->assertTrue($cond);

        $ai->input("hitung 2*2");
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && ($out['text'][0]==4);
        $this->assertTrue($cond);

        $ai->input("hitung 100/100");
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && ($out['text'][0]==1);
        $this->assertTrue($cond);

        $ai->input("hitung 2-10");
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && ($out['text'][0]==-8);
        $this->assertTrue($cond);


        $ai->input("hitung 2**5");
        $exe = $ai->execute();
        $out = $ai->output();
        $cond = $exe && ($out['text'][0]==32);
        $this->assertTrue($cond);

        return true;
    }
}
