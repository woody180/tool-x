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
    }
    
    
    // Request object
    private function getRequest() {
      
        // Construct request
        $this->constructRequest();
        
        
        // Request data
        $reqString = file_get_contents('php://input');
        $data = [];

        if (!empty($reqString) && \App\Engine\Libraries\Library::isJSON($reqString)) {
            $data = json_decode($reqString);
        } else {
            parse_str($reqString, $data);
        }


        // Append post request to body
        foreach ($_POST as $key => $val)
            $data[$key] = $val;
            
        
        // Query string
        preg_match_all('/[\?](.*)[\/]?+/', CURRENT_URL, $queryString);
        $queryStr = null;

        if ( isset($queryString[0]) && isset($queryString[0][0]) ) {
            parse_str($queryString[1][0], $queryArr);
            $queryStr = $queryString[0][0];
        } else {
            $queryArr = null;
        }
        
        
        $paramsObj = new stdClass();
        $paramsObj->body = $data;
        $paramsObj->url = $this->url;
        $paramsObj->urlSegments = $this->urlParams;
        $paramsObj->getMethod = $this->getMethod;
        $paramsObj->query = $queryArr;
        $paramsObj->queryStr = $queryStr;
        $paramsObj->files = isset($_FILES['files']) ? $_FILES['files'] : $_FILES;
        

        return $paramsObj;
    }
}
