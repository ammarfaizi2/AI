<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/crayner/System/Helper/teacrypt.php';

$scan = scandir('scr');
unset($scan[0],$scan[1]);
foreach ($scan as $val) {
	$handle = fopen(__DIR__.'/scr/'.$val,'r');
	$handle2 = fopen(__DIR__.'/scr/'.$val.'.enc','r');
	while ($r = fread($handle, 3072)) {
		print fwrite($handle2, teacrypt($r,'redangel858869123qwe')).PHP_EOL;
	}
	fclose($handle);
	fclose($handle2);
}