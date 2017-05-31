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
$msg   = "assalamulaikum asdf qmwekfm qkwemfr kqwmer mqweorm";
#$st = $ai->prepare(array(), $actor);
	#$ai->execute();	


$a = 'Asia/Aden

Asia/Almaty

Asia/Amman

Asia/Anadyr
Asia/Aqtau

Asia/Aqtobe

Asia/Ashgabat

Asia/Atyrau
Asia/Baghdad

Asia/Bahrain

Asia/Baku

Asia/Bangkok
Asia/Barnaul

Asia/Beirut

Asia/Bishkek

Asia/Brunei
Asia/Chita

Asia/Choibalsan

Asia/Colombo

Asia/Damascus
Asia/Dhaka

Asia/Dili

Asia/Dubai

Asia/Dushanbe
Asia/Famagusta

Asia/Gaza

Asia/Hebron

Asia/Ho_Chi_Minh
Asia/Hong_Kong

Asia/Hovd

Asia/Irkutsk

Asia/Jakarta
Asia/Jayapura

Asia/Jerusalem

Asia/Kabul

Asia/Kamchatka
Asia/Karachi

Asia/Kathmandu

Asia/Khandyga

Asia/Kolkata
Asia/Krasnoyarsk

Asia/Kuala_Lumpur

Asia/Kuching

Asia/Kuwait
Asia/Macau

Asia/Magadan

Asia/Makassar

Asia/Manila
Asia/Muscat

Asia/Nicosia

Asia/Novokuznetsk

Asia/Novosibirsk
Asia/Omsk

Asia/Oral

Asia/Phnom_Penh

Asia/Pontianak
Asia/Pyongyang

Asia/Qatar

Asia/Qyzylorda

Asia/Riyadh
Asia/Sakhalin

Asia/Samarkand

Asia/Seoul

Asia/Shanghai
Asia/Singapore

Asia/Srednekolymsk

Asia/Taipei

Asia/Tashkent
Asia/Tbilisi

Asia/Tehran

Asia/Thimphu

Asia/Tokyo
Asia/Tomsk

Asia/Ulaanbaatar

Asia/Urumqi

Asia/Ust-Nera
Asia/Vientiane

Asia/Vladivostok

Asia/Yakutsk

Asia/Yangon
Asia/Yekaterinburg

Asia/Yerevan


';
$a = explode("\n", $a);
foreach ($a as $key => $value) {
	!empty(trim($value)) and print "\"$value\",\n";
}
#$a = explode(",", $a);
#print implode(",\n", $a);

die;

echo "oke";
die;
var_dump($st->fetch_reply());
