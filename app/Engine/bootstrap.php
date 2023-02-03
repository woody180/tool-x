<?php

session_start();

// Configurations
require_once dirname(__DIR__) . '/Config/urls.php';
require_once APPROOT . '/Config/database.php';
require_once APPROOT . '/Config/app.php';
require_once APPROOT . '/Config/routes.php';
require_once APPROOT . '/Config/helpers.php';
require_once APPROOT . '/Config/libraries.php';
require_once APPROOT . '/Config/boot.php';

// Display errors
if (ERROR_HANDLING) 
    ini_set('display_errors', 1);
else
    ini_set('display_errors', 0);


// Adding singeton patterns
$singletones = glob(APPROOT . "/Singleton/*.php");
foreach ($singletones as $st) require_once $st;

// Base helper files
require_once APPROOT . "/Engine/Helpers/engineToolHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineDebuggingHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineUrlHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineFormHelpers.php";



// Multilingual
require_once APPROOT . "/Engine/Libraries/Languages.php";   
if (MULTILINGUAL)
{
    $langList = \App\Engine\Libraries\Languages::list();
    
    if (isset($_SESSION['lang'])) {
        
        if (is_null(urlSegments('first')) || urlSegments('first') != \App\Engine\Libraries\Languages::active()) {
            
            $urlSegments = urlSegments() === '/' ? '' : urlSegments();
            return header('Location: ' . baseUrl($urlSegments));
        }
    }
}



// Helper library
if (!empty(CUSTOM_HELPERS)) {
    foreach (CUSTOM_HELPERS as $helperFile) {
        if (!file_exists(APPROOT . "/Helpers/{$helperFile}.php")) die("Wrong helper file path for - <b>{$helperFile}.php</b>");

        require_once APPROOT . "/Helpers/{$helperFile}.php";
    }
}


// Include image resizer library
foreach (LIBRARIES as $lib) {
    require_once APPROOT . "/Engine/Libraries/{$lib}.php";
}


// Include template engine
require_once APPROOT . "/Engine/TemplateEngine/Extension/ExtensionInterface.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/ResolveTemplatePath.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/ResolveTemplatePath/NameAndFolderResolveTemplatePath.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/ResolveTemplatePath/ThemeResolveTemplatePath.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Data.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Directory.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/FileExtension.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Folders.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Func.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Functions.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Name.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Template.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Template.php";
require_once APPROOT . "/Engine/TemplateEngine/Template/Theme.php";
require_once APPROOT . "/Engine/TemplateEngine/Extension/Asset.php";
require_once APPROOT . "/Engine/TemplateEngine/Extension/URI.php";
require_once APPROOT . "/Engine/TemplateEngine/Engine.php";


// Composer autoload
if (file_exists(APPROOT . '/Helpers/vendor/autoload.php')) {
    require_once APPROOT . '/Helpers/vendor/autoload.php';
}


// RedBeanPHP model initialization function
if (DATABASE) {
    require_once APPROOT . "/Engine/Database/Initialization.php";
}


// Load files on application boot
foreach (AUTOBOOT_FILES as $file) {
    require_once APPROOT . "/Boot/{$file}.php";
}


// Validation library
require_once APPROOT . '/Engine/Libraries/Validation.php';

// Router
// require_once APPROOT . '/Engine/Libraries/Library.php';
require_once APPROOT . '/Engine/Libraries/RequestResponseTrait/RequestTrait.php';
require_once APPROOT . '/Engine/Libraries/RequestResponseTrait/ResponseTrait.php';
require_once APPROOT . '/Engine/Libraries/Router.php';

// CSRF Protection
if (CSRF_PROTECTION) {
    if (!isset($_SESSION['csrf_token'])) {
        if (!CSRF_REFRESH) {
            if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

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

// Additional route files
foreach (ROUTES_PATH as $path) {
    $dir = APPROOT . "/Routes/{$path}";
    if (is_dir($dir)) {
        $classes = glob(APPROOT . "/Routes/{$path}/*.php");
        foreach ($classes as $class) {
            $classPath = explode(APPROOT, $class)[1];
            $className = pathinfo($classPath)['filename'];
            include APPROOT . "$classPath";
        }
    }
}
