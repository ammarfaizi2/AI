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
		$ai->execute();
		$out = $ai->output();
		$cond = strpos(strtolower($out['text'][0]), "how are you")!==false;
		$this->assertTrue($cond);
	}
}