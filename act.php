<?php
require __DIR__ . '/vendor/autoload.php';
define('data', __DIR__.'/data');
is_dir(data) or mkdir(data);
use System\AI;





die;
$ai = new AI();
$act = "Ammar Faizi";
$msg = "i_anime 31765";
$st = $ai->prepare($msg, $act);
$st->execute();
var_dump($st->fetch_reply());
