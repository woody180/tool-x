<?php

trait ResponseTrait {
    
    public function getResponse() {
        return $this;
    }
       
    
    // Respond as JSON
    public function send($obj) {
        header("Content-Type: application/json; charset=UTF-8");
        echo \App\Engine\Libraries\Library::toJSON($obj);
    }
    
    
    // Response code
    public function status(int $response_code) {
        http_response_code($response_code);
        return $this;
    }


    // Render veiw
    public function render(string $viewPath, array $arguments = []) {
        $templates = new League\Plates\Engine(APPROOT . "/Views");
        echo $templates->render($viewPath, $arguments);
    }
}
