<?php

/**
 * AI storage, data and logs.
 */
if (!defined("data")) {
	define("data", __DIR__."/data");
	define("logs", __DIR__."/logs");
	define("storage", __DIR__."/storage");
}

/**
 * L Virtual.
 */
if (!defined("PHPVIRTUAL_DIR")) {
	define("PHPVIRTUAL_URL", "https://webhooks.redangel.ga/virtual/php");
	define("PHPVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/php");
	define("JAVAVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/java");
	define("RUBYVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/ruby");
	define("CVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/c");
}