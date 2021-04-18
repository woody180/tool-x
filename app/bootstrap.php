<?php

// Configurations
require_once 'Config/urls.php';
require_once 'Config/database.php';
require_once 'Config/app.php';

// Display errors
if (ERROR_HANDLING) ini_set('display_errors', 1);

// Helper library
require_once 'Helpers/Library.php';

// Composer autoload
require_once 'Helpers/vendor/autoload.php';
if (DATABASE) {
    require_once 'Libraries/rb.php';
    require_once 'Database/Connection.php';

    new \App\Database\Connection();
}

// Validation library
require_once 'Libraries/Validation.php';

// Router
require_once 'Libraries/RequestResponseTrait/RequestTrait.php';
require_once 'Libraries/RequestResponseTrait/ResponseTrait.php';
require_once 'Libraries/Router.php';

// Redirect to HTTPS
if (FORCE_SECURE_REQUESTS) {
    if(strtolower($_SERVER['REQUEST_SCHEME']) != 'https') {
        $server     = $_SERVER;
        $hostname   = "https://{$server['HTTP_HOST']}";
        $reqUri     = $server['REQUEST_URI'];
        $fullUrl    = $hostname . $reqUri;
        return header('Location: ' . $fullUrl);
    }
    if (preg_match('/www/', $_SERVER['HTTP_HOST'])) return header("Location: " . URLROOT);
}

$classes = glob(APPROOT . '/Routes/*.php');
foreach ($classes as $class) {
    
    $classPath = explode(APPROOT, $class)[1];
    $className = pathinfo($classPath)['filename'];
    include APPROOT . "$classPath";
}
