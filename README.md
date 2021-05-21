# Router
## Creating routes

Go to the app/Routes directory. Create route file add add two lines to it...
```
<?php

use App\Engine\Libraries\Router;
$router = Router::getInstance();
```

## Router HTTP verbs

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

## Router placeholder

| Placeholders      | Description |
| ----------- | ----------- |
|(:continue)|Continues url segments|
|(:alpha)|Only alphabetical characters|
|(:num)|Only numeric characters|
|(:alphanum)|Only alphabetical and numeric characters|
|(:segment)|Secured url characters such as dashes and low dashes, numbers and alphabetical characters|


## Router configuration
Configuration file can be found in ```app\Config\routes.php``` file
Add route path without app\Routes directory

```['Back', 'Front']```

Rotues will be search in app\Routes\Back and app\Routes\Front - automatically.

# Method spoofing
In some cases it is necessary use put, patch or some other request. In this case you can trait post request as some other.

Check example
```
<form action="" method="POST">
    <input name="_method" type="hidden" value="PUT" />
</form>
```

# CSRF Protection
Turn CSRF protection on from - app/Config/app.php and set **CSRF_PROTECTION** to **TRUE**

To add CSRF field to your form add following...
```
<form method="post">
    <?= \App\Engine\Libraries\Library::csrf_field(); ?>
</form>
```


# Middlewares

Middleware Files have to be inside the ```app/Routes``` directory. It is a third parameter of the $router http verb method.

```
$router->get('url', 'callback or Controller@method', 'middleware')
$router->get('url', 'callback or Controller@method', 'middleware/dir1/dir2/fileFuncName')
```
Middleware has to be declared as string and provide path to the middleware file without .php extension at the end.

Middleware file name must be the same as function name inside!

Middleware function receives two arguments inside as ```$request``` and ```$response```. They are the same arguments as inside the ```$router``` verb method (get, post..)

# Custom helper files
Helper files are located inside **app/Helpers** directory and all your custom helpers must located there.

To load custom helpers there are two ways - loading them globally and for individual route.

## Loading helper globally 
Go to the **app/Config/helpers.php** Directory and add helper file names in to the array, without extention names (.php)
```
CONST CUSTOM_HELPERS = ['myCustomHelperOne', 'myCustomHelperTwo'];
```

## Loading helper locally
Add custom helper inside the route file using **App\Engine\Libraries\Library** Library class. Take a look at the example below.

```
use App\Engine\Libraries\Router;
use App\Engine\Libraries\Library;

$router = Router::getInstance();

$router->get('/', function($req, $res) {

    // Loading custom helpers
    Library::helpers(['myCustomHelperOne', 'myCustomHelperTwo']);
    
    $res->render('welcome', [
        'title' => 'APP Title',
        'description' => 'This is the APP description'
    ]);
});
```

# Render views

Views are under ```app/Views``` directory. It's possible to render them from router as well as from controller file. The render method is under router/controller ```$response``` argument

```
    $router->get('', function($request, $response) {
        return $response->render('path/to/view'); // Without extension name
    });
```

# Validation

Validation seats under ```App\Engine\Libraries\Validation``` namespace. 

1. Use validation - ``` use  App\Engine\Libraries\Validation;```
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
                'name|Name' => 'required|alpha',
                'username|UserName' => 'required|min[4]|max[20]|alpha_num',
                'email|eMail' => 'valid_email|min[5]',
                'password|Password' => 'min[5]'
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

# Pagination

```
$totalPages = R::count('pages');
$currentPage = $_GET["page"] ?? 1;
if ($currentPage < 1 OR $currentPage > $totalPages) $currentPage = 1;
$limit = 12;
$offset = ($currentPage - 1) * $limit;  
$pagingData = Library::pager([
    'total' => $totalPages,
    'limit' => $limit,
    'current' => $currentPage
]); 
$pages = R::find("pages", "order by timestamp asc limit $limit offset $offset");
```

# CLI

It is possible to create routes and controllers using CLI commands

- Create route - ``` php cli make:routes filename httpVerb ```
- Create controller - ``` php cli make:controllers filename methodName ```
- Create restful routes and controllers - ``` php cli make:restful Blog/Articles ```