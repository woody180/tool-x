<?php

// Application config
// define("URLROOT", "http://framework.localhost");
define("URLROOT", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
define("PUBLIC_DIR", URLROOT . "/assets");
define("APPROOT", dirname(dirname(__FILE__)));
define("FORCE_SECURE_REQUESTS", FALSE);
define("CURRENT_URL", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
date_default_timezone_set("Asia/Tbilisi"); // https://www.php.net/manual/en/timezones.php