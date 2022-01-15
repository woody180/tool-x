<?php namespace App\Controllers;

use App\Engine\Libraries\Languages;

class HomeController {
    
    public function index($req, $res) {

        $res->render('welcome', [
            'title' => 'APP Title'
        ]);
    }
}