<?php

use App\Engine\Libraries\Router;
use App\Engine\Libraries\Library;

$router = Router::getInstance();

$router->get('/', function($req, $res) {
    
    $res->render('welcome', [
        'title' => 'APP Title',
        'description' => 'This is the APP description',
        'library' => Library::class
    ]);
});


$router->post('/', function($req, $res) {
    $res->send($req->body());
});


$router->get('one', 'PagesController@home');