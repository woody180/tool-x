<?php

// Application config
define("URLROOT", "http://localhost:8080");
// define("URLROOT", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
define("PUBLIC_DIR", URLROOT . "/assets");
define("APPROOT", dirname(dirname(__FILE__)));

if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI']))
    define("CURRENT_URL", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
