<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config.php';

use AI\AI;

$actor = "Ammar Faizi";
$input = "ask";


$start = microtime(true);
$ai = new AI();
$ai->input($input, $actor);
$ai->execute();
$out = $ai->output();



$finish = microtime(true);

var_dump(
    array(
    	"output" => $out,
        "execution time" => ($finish - $start)
    )
);
