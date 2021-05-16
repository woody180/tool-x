<?php

trait RequestTrait {
    
    private $getMethod;
    private $request;
    private $urlParams;
    private $url;
    
    
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
    }


    // Get url segment
    public function getSegment(int $index) {
        return $this->urlParams[$index - 1] ?? null;
    }


    public function body(string $index = null) {
                
        // Request data
        $reqString = file_get_contents('php://input');
        $data = [];

        if (!empty($reqString) && \App\Engine\Libraries\Library::isJSON($reqString)) {
            $data = json_decode($reqString);
        } else {
            parse_str($reqString, $data);
        }

        if (isset($data['_method'])) unset($data['_method']);

        // Append post request to body
        foreach ($_POST as $key => $val)
            $data[$key] = $val;


        if ($index)
            return $data[$index] ?? null;
        
        return $data;
    }


    // Url
    public function url() {
        return $this->url;
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
