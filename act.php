<?php
require __DIR__ . '/loader.php';
use System\AI;
$ai = new AI();
$msg = "ctranslate id ru halo";
$act = "Ammar Faizi";
$st = $ai->prepare($msg,$act);
$st->execute();
var_dump($st->fetch_reply());