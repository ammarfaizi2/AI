<?php
require __DIR__.'/vendor/autoload.php';

define('data',__DIR__.'/data');
is_dir(data) or mkdir(data);
use System\AI;
$ai = new AI();
$msg = "ctranslate id en apa kabar?";
$act = "Ammar Faizi";
$st = $ai->prepare($msg, $act);
$st->execute();
var_dump($st->fetch_reply());