<?php namespace one\more\time;


class PagesController {
    
    public function home($req, $res) {
        
        return $res->render('welcome', [
            'title' => 'Heading',
            'description' => 'Something like content'
        ]);
    }
}
