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


$a = 'Africa/Asmera

Africa/Timbuktu

America/Argentina/ComodRivadavia

America/Atka
America/Buenos_Aires

America/Catamarca

America/Coral_Harbour

America/Cordoba
America/Ensenada

America/Fort_Wayne

America/Indianapolis

America/Jujuy
America/Knox_IN

America/Louisville

America/Mendoza

America/Montreal
America/Porto_Acre

America/Rosario

America/Santa_Isabel

America/Shiprock
America/Virgin

Antarctica/South_Pole

Asia/Ashkhabad

Asia/Calcutta
Asia/Chongqing

Asia/Chungking

Asia/Dacca

Asia/Harbin
Asia/Istanbul

Asia/Kashgar

Asia/Katmandu

Asia/Macao
Asia/Rangoon

Asia/Saigon

Asia/Tel_Aviv

Asia/Thimbu
Asia/Ujung_Pandang

Asia/Ulan_Bator

Atlantic/Faeroe

Atlantic/Jan_Mayen
Australia/ACT

Australia/Canberra

Australia/LHI

Australia/North
Australia/NSW

Australia/Queensland

Australia/South

Australia/Tasmania
Australia/Victoria

Australia/West

Australia/Yancowinna

Brazil/Acre
Brazil/DeNoronha

Brazil/East

Brazil/West

Canada/Atlantic
Canada/Central

Canada/East-Saskatchewan

Canada/Eastern

Canada/Mountain
Canada/Newfoundland

Canada/Pacific

Canada/Saskatchewan

Canada/Yukon
CET

Chile/Continental

Chile/EasterIsland

CST
CDT
Cuba

EET

Egypt

Eire
EST

EST
EDT

Etc/GMT

Etc/GMT


Etc/GMT



Etc/GMT




Etc/GMT




Etc/GMT



Etc/GMT



Etc/GMT



Etc/GMT



Etc/GMT


Etc/GMT



Etc/GMT



Etc/GMT



Etc/GMT


Etc/GMT-


Etc/GMT-


Etc/GMT-



Etc/GMT-


Etc/GMT-



Etc/GMT-



Etc/GMT-



Etc/GMT-

Etc/GMT-


Etc/GMT-


Etc/GMT-


Etc/GMT-

Etc/GMT-


Etc/GMT-


Etc/GMT-


Etc/GMT

Etc/Greenwich

Etc/UCT

Etc/Universal

Etc/UTC
Etc/Zulu

Europe/Belfast

Europe/Nicosia

Europe/Tiraspol
Factory

GB

GB-Eire

GMT
GMT



GMT-


GMT


Greenwich
Hongkong

HST

Iceland

Iran
Israel

Jamaica

Japan

Kwajalein
Libya

MET

Mexico/BajaNorte

Mexico/BajaSur
Mexico/General

MST

MST
MDT

Navajo
NZ

NZ-CHAT

Pacific/Johnston

Pacific/Ponape
Pacific/Samoa

Pacific/Truk

Pacific/Yap

Poland
Portugal

PRC

PST
PDT

ROC
ROK

Singapore

Turkey

UCT
Universal

US/Alaska

US/Aleutian

US/Arizona
US/Central

US/East-Indiana

US/Eastern

US/Hawaii
US/Indiana-Starke

US/Michigan

US/Mountain

US/Pacific
US/Pacific-New

US/Samoa

UTC

W-SU
WET

Zulu






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
