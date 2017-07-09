<?php

namespace tests\Command;

use AI\AI;
use PHPUnit\Framework\TestCase;

class GoogleTranslateTest extends TestCase
{
	public function testSimpleCTranslate()
	{
		$ai = new AI();
		$ai->input("ctranslate id en halo, apa kabar?");
		$exe = $ai->execute();
		$out = $ai->output();
		$cond = $exe && strpos(strtolower($out['text'][0]), "how are you")!==false;
		$this->assertTrue($cond);

		$ai = new AI();
		$ai->input("ctranslate id en jam berapa sekarang?");
		$exe = $ai->execute();
		$out = $ai->output();
		$cond = $exe && strpos(strtolower($out['text'][0]), "what time")!==false;
		$this->assertTrue($cond);

		$ai = new AI();
		$ai->input("ctranslate id ja halo");
		$exe = $ai->execute();
		$out = $ai->output();
		$cond = $exe && strpos(strtolower($out['text'][0]), "moshi")!==false;
		$this->assertTrue($cond);
	}
}