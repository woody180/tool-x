# Router
## Creating routes

Go to the app/Routes directory. Create route file and add two lines to it...
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

## Create routes with CLI
```
php cli make:routes optional/path/routerName
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

# Models

A model is a place to put validation and business logic. 

## Create model

Models are located inside **app/Models** directory. It is necessary to follow RedBeanPHP model name convention for naming model file - **Model_Pages.php**.

Inside model add the class and extend it with RedBeanPHP **RedBean_SimpleModel** class.

To initialize model add function inside the controller or router function - **initModel('Pages')**.

```
<?php namespace App\Controllers;

use App\Engine\Libraries\Library;
use \R as R;

class HomeController {
    
    public function index($req, $res) {

        // Initialize model
        $pagesModel = initModel('Pages');

        $res->render('welcome', [
            'title' => 'APP Title',
            'library' => Library::class
        ]);
    }
}
```

Make sure SQL is enabled from **app/Config/database.php** file - **define("DATABASE", TRUE);**.

```
class Model_Pages extends RedBean_SimpleModel {

    public function open() {
        global $lifeCycle;
        $lifeCycle .= "called open: ".$this->id;
    }

    public function dispense() {
        global $lifeCycle;
        $lifeCycle .= "called dispense() ".$this->bean;
    }

    public function update() {
        global $lifeCycle;
        $lifeCycle .= "called update() ".$this->bean;
    }

    public function after_update() {
        global $lifeCycle;
        $lifeCycle .= "called after_update() ".$this->bean;
    }

    public function delete() {
        global $lifeCycle;
        $lifeCycle .= "called delete() ".$this->bean;
    }

    public function after_delete() {
        global $lifeCycle;
        $lifeCycle .= "called after_delete() ".$this->bean;
    }
}
```

It is posible to add custom method to the model. They can be available from initModel('Pages'); function;

```
// Initialize model
$pagesModel = initModel('Pages');

// Get custom method
$pagesModel->customMethod();
```



# Method spoofing
In some cases it is necessary use put, patch or some other request. In this case you can trait post request as some other.

Check example
```
<form action="" method="POST">
    <input name="_method" type="hidden" value="PUT" />
</form>
```
Or add function
```
<form action="" method="POST">
    <?= setMethod("PUT") ?>
</form>
```

# CSRF Protection

## CSRF hidden field
Turn CSRF protection on from - app/Config/app.php and set **CSRF_PROTECTION** to **TRUE**

To add CSRF field to your form add following...
```
<form method="post">
    <?= csrf_field(); ?>
</form>
```

## Check CSRF value / hash
```
<?= csrf_hash() ?>
```

# Form helpers
Prevents losing form field values with **getForm('field_name')** function.

```
<form method="post">

    <input name="fullname"  value="<?= getForm('fullname') ?>" />
    <input name="username"  value="<?= getForm('username') ?>" />
    <input name="email"     value="<?= getForm('email') ?>" />
    <input name="password"  value="" />

    <button type="submit">Submit form</button>
</form>
```

Before using **getForm** helper function, you must set if from controller or router using **setForm($requestBody)** function. As a parameter it takes request body.

```
$router->post('users/register', function($req, $res) {
    
    // Storing request body with setForm function
    setForm($req->body());

    // Redirect back to login view
    return $res->redirect(baseUrl('users/login'));
});
```

# Request & Respons method

You can find **\$request** and **\$response** variables in to the routes callback or inside the controller method callback as an arguments. Check out the example
```
<?php namespace App\Controllers\Items;

use App\Engine\Libraries\Library;
use App\Engine\Libraries\Validation;
use \R as R;

class ItemsController {
    public function new($req, $res) {
        
        // Request variable method
        return $res->redirectBack();
    }
}

```

## Avalable request methods
### Request
| Methods     | Description |
| ----------- | ----------- |
|$req->body()|Getting request body. Takes optional string argument.|
|$req->getSegment(int 2)|Getting segment of the url|
|$req->urlSegments()|Getting segments of the url as array|
|$req->getMethod()|Request method|
|$req->query(string $key)|Query param|
|$req->queryStr()|Query parameters as string|
|$req->files()|Getting file request|
|$req->isAjax()|Check if request is ajax|


## Avalable response methods
### Response
| Methods      | Description |
| ----------- | ----------- |
|$res->redirect(string $url)|Redirect someware|
|$res->redirectBack()|Getting segment of the url|
|$res->render(string $pathToView)|Rendering view|
|$res->status(int $response_code)|Setting status code|
|$res->send(array $data)|Sends back json data|


# URL helpers
## Get base url

To get progect base url, use - **baseUrl()** function. It takes optional parametes where you can add new url based on base url. See example

```
<?= baseUrl() ?>                <!-- http://sitename.com -->
<?= baseUrl('users/login') ?>   <!-- http://sitename.com/users/login -->
```

## URL segments
To get url without site base url with function **getSegments()**. It takes optional parametes where you can set url indexes. 

For example you current url is **http://sitename.com/users/login**:
```
<?= getSegments() ?>    <!-- returns - users/login -->
<?= getSegments(2) ?>   <!-- returns - login -->
```
## Getting query string
```
<?= query() // Takes optional numeric argument ?>
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
2. alpha_spaces
3. numeric
4. alpha_num
5. alpha_num_spaces
6. valid_email
7. valid_url
8. valid_slug
9. min[]
10. max[]
11. required
12. valid_input

## Field error message
```
<form>
    <div>

        <input name="username" />

        <?php if ($library::hasFlashData('errors')): ?>
            <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', 
            $library::getFlashData('errors')->username ?? []) ?></p>
        <?php endif; ?>

    </div>
</form>
```

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