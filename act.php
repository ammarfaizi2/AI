<?php
require __DIR__.'/vendor/autoload.php';
define('data', __DIR__.'/data');
is_dir(data) or mkdir(data);
use System\AI;

print file_put_contents('test', json_encode(get_declared_classes(), 128));
die;
$ai = new AI();
$msg = "eval ;";
$act = "Ammar Faizi";
$st = $ai->prepare($msg, $act);
$st->execute();
var_dump($st->fetch_reply());
