<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config.php';

use AI\AI;

$actor = "Ammar Faizi";
$input = "Halo";


$start = microtime(true);
$ai = new AI();
$ai->input($input, $actor);
// $ai->execute();



$finish = microtime(true);

var_dump(
    array(
        "execution time" => ($finish - $start)
    )
);
