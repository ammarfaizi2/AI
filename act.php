<?php
require __DIR__ . '/vendor/autoload.php';
define('data', __DIR__.'/data');
is_dir(data) or mkdir(data);
use System\AI;

$ai = new AI();
$act = "Ammar Faizi";
$msg = "whatanime http://www.nautiljon.com/images/breves/00/65/psycho-pass_movie_premier_trailer_2456.jpg?1417370555";
$st = $ai->prepare($msg, $act);
$st->execute();
var_dump($st->fetch_reply());
