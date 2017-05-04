<?php
require __DIR__ . '/loader.php';
use System\AI;
$ai = new AI();
$msg = "halo carik";
$act = "Ammar Faizi";
$st = $ai->prepare($msg,$act);
$st->execute();