<?php

use App\Engine\Libraries\Router;
use App\Engine\Libraries\Library;

$router = Router::getInstance();

$router->get('/', 'HomeController@index');