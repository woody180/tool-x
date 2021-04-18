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

        if (!empty($reqString) && \App\Helpers\Library::isJSON($reqString)) {
            $data = json_decode($reqString);
        } else {
            parse_str($reqString, $data);
        }


        // Append post request to body
        foreach ($_POST as $key => $val)
            $data[$key] = $val;
            
        
        // Query string
        $queryString = explode('?', CURRENT_URL)[1] ?? null;
        if ($queryString)
            parse_str($queryString, $queryArr);
        else
            $queryArr = null;
        
        
        $paramsObj = new stdClass();
        $paramsObj->body = $data;
        $paramsObj->url = $this->url;
        $paramsObj->urlSegments = $this->urlParams;
        $paramsObj->getMethod = $this->getMethod;
        $paramsObj->query = $queryArr;
        $paramsObj->files = isset($_FILES['files']) ? $_FILES['files'] : $_FILES;
        

        return $paramsObj;
    }
}
