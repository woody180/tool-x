<?php


class PagesController {
    
    public function home($req, $res) {
        
        return $res->render('welcome', [
            'title' => 'Heading',
            'description' => 'Something like content'
        ]);
    }
}
