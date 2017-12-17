<?php

/**
 * Test with defined constant AI_DIR
 *
 */
define("AI_DIR", realpath("../ai_data"));

require __DIR__ . "/../vendor/autoload.php";

$ai = new AI();
$ai->execute();
print $ai->output();
