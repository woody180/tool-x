<?php

use App\Engine\Libraries\Router;

$router = Router::getInstance();

$router->get('/', 'HomeController@index');


$router->get('message', function($req, $res) {

    setFlashData('message', 'This is sample message');

    return $res->redirectBack();
});