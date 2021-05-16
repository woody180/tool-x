<?php namespace App\Controllers\Api\Blog;

use App\Engine\Libraries\Library;

class Articles {
    
    // Add new view
    public function new($req, $res) {
        
        return $res->render("Api/Blog/Articles_new");
    }


    // Create view
    public function create($req, $res) {
        
        $body = $req->body();
        
        $Articles = \R::dispense("articles");
        $Articles->ordering = 1;
        $Articles->timestamp = time();
        $Articles->import($body);

        \R::store($Articles);

        return $res->redirect(URLROOT . "/api/blog/articles");
    }


    // All items
    public function index($req, $res) {
        
        // Pagination
        $totalPages = \R::count("articles");
        $currentPage = $req->query('page');
        if ($currentPage < 1 OR $currentPage > $totalPages) $currentPage = 1;
        $limit = 12;
        $offset = ($currentPage - 1) * $limit;
    
        $pagingData = Library::pager([
            "total" => $totalPages,
            "limit" => $limit,
            "current" => $currentPage
        ]);

        $Articles = \R::findAll("articles", "order by timestamp asc limit $limit offset $offset");
        
        return $res->render("Api/Blog/Articles", [
            "Articles" => $Articles,
            "paging" => $totalPages > $limit ? $pagingData : null
        ]);
    }


    // Show view
    public function show($req, $res) {

        $id = $req->getSegment(4);

        $Articles = \R::load("articles", $id);

        Library::dd($Articles);
    }


    // Edit view
    public function edit($req, $res) {

        $id = $req->getSegment(4);

        $Articles = \R::load("articles", $id);

        return $res->render("Api/Blog/Articles_edit", [
            "Articles" => $Articles
        ]);
    }


    // Update
    public function update($req, $res) {

        $id = $req->getSegment(4);

        $body = $req->body();

        $Articles = \R::load("articles", $id);

        $Articles->import($body);

        \R::store($Articles);

        return $res->redirect(URLROOT . "/api/blog/articles");
    }


    // Delete
    public function delete($req, $res) {

        $id = $req->getSegment(4);
        $body = $req->body("delete");

        foreach ($body as $id) {
            $item = \R::load("articles", $id);
            \R::trash($item);
        }

        return $res->redirect(URLROOT . "/api/blog/articles");
    }

}
        