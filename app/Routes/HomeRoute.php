<?php

use App\Libraries\Router;
use App\Helpers\Library;

$router = Router::getInstance();

$router->get('/', function($req, $res) {
    
    $res->render('welcome', [
        'title' => 'title',
        'content' => 'something'
    ]);
});


$router->get('one', 'PagesController@home');