<?php

trait RequestTrait {
    
    private $getMethod;
    private $request;
    private $urlParams;
    private $url;
    private $isDone = false;
    
    
    // Construct request url data...
    protected function constructRequest() {
        
        // Get requested url
        $url = explode(URLROOT, CURRENT_URL)[1];
        $url = $url != '/' ? ltrim(rtrim($url, '/'), '/') : '/';
        $this->url = filter_var($url, FILTER_SANITIZE_STRING);
        
        // Url parameters
        $this->urlParams = $this->url == '/' ? null : explode('/', $this->url);
        
        // Get request method
        $this->getMethod = strtolower($_SERVER["REQUEST_METHOD"]);

        $methords = ['put', 'patch', 'delete'];

        foreach ($_POST as $key => $val) {
            if ($key == '_method' && gettype($val) == 'string' && in_array(strtolower($val), $methords)) {
                $this->getMethod = strtolower($val);
                unset($_POST['_method']);
            }
        }

        setFlashData('previous_url', urlSegments());
        $this->body();
    }


    // Check if request is ajax
    public function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0) {
            return true;
        }

        return false;
    }
    

    // Get url segment
    public function getSegment(int $index, bool $withQeuryString = false) {
        
        $param = $this->urlParams[$index - 1] ?? null;
        
        if (!$param) return null;
        
        if ($withQeuryString) {
            return $param;
        } else {
            return explode('?', $param)[0];
        }
    }


    public function body(string $index = null) {
                
        // Request data
        $reqString = file_get_contents('php://input');
        $data = [];

        if (!empty($reqString) && isJSON($reqString)) {
            $data = json_decode($reqString, true);
        } else {
            parse_str($reqString, $data);
        }

        if (isset($data['_method'])) unset($data['_method']);

        // Append post request to body
        foreach ($_POST as $key => $val)
            $data[$key] = $val;


        if (CSRF_PROTECTION && $this->getMethod() === 'post' && !$this->isDone) {
                    
            // If token inside the request body
            if (!isset($data['csrf_token'])) return abort(['code' => 403]);

            // Compare tokens
            if ($data['csrf_token'] != $_SESSION['csrf_token']) return abort(['code' => 403]);

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $this->isDone = true;
        }

        if (isset($data['csrf_token'])) unset($data['csrf_token']);


        if ($index)
            return $data[$index] ?? null;
        
        return $data;
        
    }


    // Url
    public function url() {

        $res = preg_replace('/[\/]?\?.*/', '', $this->url);
        
        if (!strlen($res)) return '/';
        return $res;
    }


    // Url segments array
    public function urlSegments() {
        return $this->urlParams;
    }


    // Get method
    public function getMethod() {
        return $this->getMethod;
    }


    // Get query
    public function query(string $key = null) {
        // Query string
        preg_match_all('/[\?](.*)[\/]?+/', CURRENT_URL, $queryString);
        $queryStr = null;

        if ( isset($queryString[0]) && isset($queryString[0][0]) ) {
            parse_str($queryString[1][0], $queryArr);
            $queryStr = $queryString[0][0];
        } else {
            $queryArr = null;
        }

        if ($key) {
            return $queryArr[$key] ?? null;
        } else {
            return $queryArr;
        }

    }


    // Get query string
    public function queryStr() {
        // Query string
        preg_match_all('/[\?](.*)[\/]?+/', CURRENT_URL, $queryString);
        $queryStr = null;

        if ( isset($queryString[0]) && isset($queryString[0][0]) ) {
            parse_str($queryString[1][0], $queryArr);
            $queryStr = $queryString[0][0];
        } else {
            $queryArr = null;
        }

        return $queryStr;
    }


    // Get files
    public function files(string $key = null) {
        if ($key) {
            return $_FILES[$key] ?? null;
        }
        
        return $_FILES;
    }
    
    
    // Request object
    private function getRequest() {

        // Construct request
        $this->constructRequest();

        return $this;
    }
}
