<?php
require __DIR__ . '/vendor/autoload.php';

/**
 * Define `data` constant and create folder for AI data
 */
define('data', __DIR__.'/data');
is_dir(data) or mkdir(data);

use AI\AI;

$ai = new AI();
$actor = "Ammar Faizi";
$msg   = "assalamulaikum";
$st = $ai->prepare($msg, $actor);
$st->set_timezone("Asia/Jakarta");
$ai->execute();
var_dump($st->fetch_reply());
