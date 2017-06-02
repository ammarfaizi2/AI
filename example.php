<?php
require __DIR__ . '/vendor/autoload.php';

/**
 * Define `data` constant and create folder for AI data
 */
define('data', __DIR__.'/data');
is_dir(data) or mkdir(data);

use AI\AI;

$start = microtime(true);
$ai = new AI();
$actor = "Ammar Faizi";
$msg   = "jadwal sholat surakarta";
$st = $ai->prepare($msg, $actor);
$st->set_timezone("Asia/Jakarta");
$ai->execute();
var_dump($st->fetch_reply());

$finish = microtime(true);
var_dump(array(
		"execution time" => ($finish - $start)
	));
