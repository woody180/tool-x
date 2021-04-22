# Creating routes

Go to the app/Routes directory. Create route file add add two lines to it...
```
<?php

use App\Libraries\Router;
$router = Router::getInstance();
```

# Router HTTP verbs

```
$router->get('/', function($req, $res) {});
$router->post('/', function($req, $res) {});
$router->put('/', function($req, $res) {});
$router->patch('/', function($req, $res) {});
$router->delete('/', function($req, $res) {});
$router->all('/', function($req, $res) {});
$router->match('get|post', '/', function($req, $res) {});
```
Router verb method takes two arguments -  ```$request``` and ```$response```.
```
$router->get('/', function($req, $res) {

    return $res->render('welcome');
});
```

# Middlewares

Middleware file has to be inside the ```app/Rotutes``` directory. It is a third parameter of the $router http verb method.

```
$router->get('url', 'callback or Controller@method', 'middleware')
$router->get('url', 'callback or Controller@method', 'middleware/dir1/dir2/fileFuncName')
```
Middleware has to be declared as string and provide path to the middleware file without .php extension at the end.

Middleware file name must be the same as function name inside!

Middleware function receives two arguments inside as ```$request``` and ```$response```. They are the same arguments as inside the ```$router``` verb method (get, post..)

# Render views

Views are under ```app/Views``` directory. It's possible to render them from router as well as from controller file. The render method is under router/controller ```$response``` argument

```
    $router->get('', function($request, $response) {
        return $response->render('path/to/view'); // Without extension name
    });
```

# Validation

Validation seats under ```App\Libraries\Validation``` namespace. 

1. Use validation - ``` use  App\Libraries\Validation;```
2. Initialize - ``` $validation = new Validation(); ```
3. Take a look at example below

```
$router->post('api/one', function($req, $res) {

    // Get request data
    $body = $req->body;

    // Valdiate request data
    $valiate = $validation
            ->with($body)
            ->rules([
                'name' => 'required|alpha',
                'username' => 'required|min[4]|max[20]|alpha_num',
                'email' => 'valid_email|min[5]',
                'password' => 'min[5]'
            ])
            ->validate();
});
```

## Avaialble validators
1. alpha
2. numeric
3. alpha_num
4. valid_email
5. valid_url
6. valid_slug
7. min[]
8. max[]
9. required
10. valid_input