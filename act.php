<?php
require __DIR__ . '/vendor/autoload.php';
define('data', __DIR__.'/data');
is_dir(data) or mkdir(data);
use System\AI;

$ai = new AI();
$act = "Ammar Faizi";
$msg = "i_anime 31765";
$st = $ai->prepare($msg, $act);
$st->execute();
var_dump($st->fetch_reply());

/**
*	@param	string	$host	Hostnya
*	@param	string	$user	Username
*	@param	string	$pass	Password
*	@param	string	$db		Database yang diakses
*	@return resources
*/
$con = mysqli_connect($host, $user, $pass, $db);

/**
*	@param	resources		$con	Hasil return mysqli_connect
*	@param	string			$query	Query yang dijalankan
*	@return	resources|bool
*/
mysqli_query($con, $query);