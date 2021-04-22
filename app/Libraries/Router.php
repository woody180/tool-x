<?php namespace App\Libraries;

use \App\Helpers\Library;

class Router {
    
    use \RequestTrait, \ResponseTrait;
    
    private $routes = [];
    private $request;
    private static $instance = null;



    private function __construct() {
        $this->request = $this->getRequest();
    }

    // Router HTTP verbs
    public function get($url, $callback, string $middleware = null) {
        $this->routes['get'][$url] = [$callback, $middleware];
    }
    public function post($url, $callback, string $middleware = null) {
        $this->routes['post'][$url] = [$callback, $middleware];
    }
    public function put($url, $callback, string $middleware = null) {
        $this->routes['put'][$url] = [$callback, $middleware];
    }
    public function patch($url, $callback, string $middleware = null) {
        $this->routes['patch'][$url] = [$callback, $middleware];
    }
    public function delete($url, $callback, string $middleware = null) {
        $this->routes['delete'][$url] = [$callback, $middleware];
    }
    public function match($methods, $url, $callback, string $middleware = null) {

        $methodsArray = explode('|', $methods);

        foreach ($methodsArray as $method)
            $this->routes[$method][$url] = [$callback, $middleware];
    }
    public function all($url, $callback, string $middleware = null) {

        $httpVerbs = ['get', 'post', 'put', 'patch', 'delete', 'options'];

        foreach ($httpVerbs as $verb)
            $this->routes[$verb][$url] = [$callback, $middleware];
    }
    
    
        
    // Placeholder to regex
    protected function checkPatternMatch() {
        $routes = [];

        foreach ($this->routes[$this->request->getMethod] as $route => $method) {
            $url = str_replace('/', '\/', $route);
            $url = str_replace('(:any)', '.*', $url);
            $url = str_replace('(:num)', '\d+', $url);
            $url = str_replace('(:alpha)', '[a-zA-Zა-ზ-]+', $url);
            $url = str_replace('(:alphanum)', '[a-zA-Zა-ზ0-9-]+', $url);

            // Push to new routes array
            $routes[$this->request->getMethod][$url] = $method;
        }

        // Find requested url
        foreach ($routes[$this->request->getMethod] as $route => $method) {

            $queryStr = !empty($this->request->query) ? '?' . http_build_query($this->request->query) : null;
            $compareTo = $queryStr ? explode($queryStr, $this->request->url)[0] : $this->request->url;
            $compareTo = empty($compareTo) ? '/' : $compareTo;

            if (preg_match_all("/" . $route . "/", $compareTo, $match)) {

                if (isset($match[0]) && isset($match[0][0]) && $match[0][0] === $compareTo) {
                    return $method;
                } else {
                    continue;
                }
            }
        }

        return 0;
    }
    
    
    private function runMiddleware($func) {
        // Check if file exists
        $file = APPROOT . "/Routes/{$func}.php";

        if (!file_exists($file))
            die('Wrong middleware path ' . $func );
    
        // check slashes
        require_once $file;

        $arr = explode('/', $func);
        $function = end($arr);
        
        $function($this->getRequest(), $this->getResponse());
    }    
    
    
    public function __destruct() {
        // Check if method exists inside the router
        if (array_key_exists($this->request->getMethod, $this->routes)) {

            // Check if user exists inside the routes method
            
            if ($this->checkPatternMatch()) {
                
                // Get callback variable
                $callback = $this->checkPatternMatch();

                // Check if callback variable is statusCode
                if (is_numeric($callback[0])) Library::notFound(['code' => $callback[0]]);
                
                // Check if $callback is callable
                if (is_callable($callback[0])) {

                    // Check if route has some middleware
                    if ($callback[1]) $this->runMiddleware($callback[1]);

                    call_user_func($callback[0], $this->getRequest(), $this->getResponse());
                } else {

                    // Controller & method array
                    $controllerMethodArray = explode('@', $callback[0]);

                    // Get controller
                    $this->currentController = $controllerMethodArray[0];

                    // Check if controller file exists
                    if (!file_exists(dirname(__DIR__) . "/Controllers/{$this->currentController}.php"))
                        Library::notFound();

                    // Require controller file
                    require_once dirname(__DIR__) . "/Controllers/{$this->currentController}.php";

                    // Instantiate controller
                    $controller = explode('/', $this->currentController);
                    $this->currentController = end($controller);
                    $this->currentController = new $this->currentController();

                    // Get method
                    $this->currentMethod = $controllerMethodArray[1];

                    // Check method inside the controller
                    if (!method_exists($this->currentController, $this->currentMethod))
                        Library::notFound();
                    
                    // Check if route has some middleware
                    if ($callback[1]) $this->runMiddleware($callback[1]);


                    // Call method and apply arguments
                    call_user_func_array([$this->currentController, $this->currentMethod], [$this->getRequest(), $this->getResponse()]);
                }
            } else {

                Library::notFound();
            }

        } else {

            Library::notFound();
        }
    }
    
    public static function getInstance() {
        if (!self::$instance) self::$instance = new Router();
        
        return self::$instance;
    }
}