<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config.php';

use AI\AI;

$start = microtime(true);
$ai = new AI();
$actor = "Ammar Faizi";
$msg   = 'i_anime 31765';
$st = $ai->prepare($msg, $actor);
$st->set_timezone("Asia/Jakarta");
$st->set_superuser(array("Ammar Faizi"));
$ai->execute();
var_dump($st->fetch_reply());


$finish = microtime(true);

var_dump(
    array(
        "execution time" => ($finish - $start)
    )
);
