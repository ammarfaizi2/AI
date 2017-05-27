<?php
require __DIR__ . '/vendor/autoload.php';
define('data', __DIR__.'/data');
is_dir(data) or mkdir(data);
use System\AI;
new \App\JadwalSholat;
$ai = new AI();
$act = "Ammar Faizi";
$msg = "hitung";
$st = $ai->prepare($msg, $act);
$st->execute();
var_dump($st->fetch_reply());
