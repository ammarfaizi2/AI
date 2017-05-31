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
$msg   = "sekarang bulan apa?";
$st = $ai->prepare($msg, $act);
$st->execute();
var_dump($st->fetch_reply());
