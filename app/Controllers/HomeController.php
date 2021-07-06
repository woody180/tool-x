<?php namespace App\Controllers;

use App\Engine\Libraries\Library;

class HomeController {
    
    public function index($req, $res) {

        $res->render('welcome', [
            'title' => 'APP Title',
            'library' => Library::class
        ]);
    }
}