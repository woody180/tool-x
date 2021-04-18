<?php


class PagesController {
    
    public function home($req, $res) {
        
        return $res->render('welcome', [
            'title' => 'Heading',
            'content' => 'Something like content'
        ]);
    }
}
