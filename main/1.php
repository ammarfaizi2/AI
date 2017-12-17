<?php

/**
 * Test with defined constant AI_DIR
 *
 */
define("AI_DIR", realpath("../ai_data"));

require __DIR__ . "/../vendor/autoload.php";

use AI\AI;

$text = "Hai, apa kabar?";
$ai = new AI($text);
$ai->execute();
print $ai->output();
