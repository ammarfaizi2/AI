<?php

/**
 * AI storage, data and logs.
 */
define("storage", __DIR__."/storage");
define("data", __DIR__."/data");
define("logs", __DIR__."/logs");

/**
 * L Virtual.
 */
define("PHPVIRTUAL_URL", "https://webhooks.redangel.ga/virtual/php");
define("PHPVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/php");
define("JAVAVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/java");
define("RUBYVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/ruby");
define("CVIRTUAL_DIR", "/home/ammar/web/webhooks/public/virtual/c");


/**
 * Create directory.
 */
is_dir(storage) or mkdir(storage);
is_dir(data) or mkdir(data);
is_dir(logs) or mkdir(logs);

