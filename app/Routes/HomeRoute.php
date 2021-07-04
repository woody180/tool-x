<?php

use App\Engine\Libraries\Router;
use App\Engine\Libraries\Library;

$router = Router::getInstance();

$router->get('/', function($req, $res) {

    $pagesModel = initModel('pages');

    return $res->send(R::load('pages', 1));

    $res->render('welcome', [
        'title' => 'APP Title',
        'description' => 'This is the APP description',
        'library' => Library::class
    ]);
});