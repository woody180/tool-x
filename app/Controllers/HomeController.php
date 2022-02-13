<?php namespace App\Controllers;

class HomeController {
    
    public function index($req, $res) {

        $res->render('welcome', [
            'title' => 'APP Title'
        ]);
    }
}