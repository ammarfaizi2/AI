#!/usr/bin/env php
<?php

if (PHP_SAPI === 'cli' && defined("PHP_BINARY") && ! empty(PHP_BINARY)) {
	foreach (scandir($dir = __DIR__."/main") as $val) {
		print shell_exec(PHP_BINARY." ".$dir."/".$val);
	}
}
