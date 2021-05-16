<?php

use App\Engine\Libraries\Router;
use App\Engine\Libraries\Library;

$router = Router::getInstance();

$router->get('api/blog/articles/new',                "Api/Blog/Articles@new");
$router->post('api/blog/articles',                   "Api/Blog/Articles@create");
$router->get('api/blog/articles',                    "Api/Blog/Articles@index");
$router->get('api/blog/articles/(:segment)',         "Api/Blog/Articles@show");
$router->get('api/blog/articles/(:segment)/edit',    "Api/Blog/Articles@edit");
$router->put('api/blog/articles/(:segment)',         "Api/Blog/Articles@update");
$router->patch('api/blog/articles/(:segment)',       "Api/Blog/Articles@update");
$router->delete('api/blog/articles/(:segment)',      "Api/Blog/Articles@delete");
        