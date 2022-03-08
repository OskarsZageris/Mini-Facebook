<?php

namespace App\Controllers;

use App\Exceptions\Exception;
use App\Database;
use App\Exceptions\FormValidationException;
use App\Exceptions\ResourceNotFoundException;
use App\Redirect;
use App\Validation\ArticleFormValidator;
use App\View;
use App\Models\Article;
use App\Validation\Errors;

class ArticleController
{
    public function index(): View
    {
        try {


            $connection = Database::connect();

            $results = $connection
                ->createQueryBuilder()
                ->select('id', 'title', 'description', 'created_at')
                ->from('article')
                ->orderBy('created_at', 'desc')
                ->executeQuery()
                ->fetchAllAssociative();
//var_dump($results);

            foreach ($results as $result) {
                $articles[] = new Article($result['title'], $result['description'], $result['created_at'], $result['id']);
            }
            return new View("Articles/index.html", [
                'articles' => $articles
            ]);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new View("404.html");
        }
    }


    public function show(array $vars): View
    {
        $connection = Database::connect();

        $result = $connection
            ->createQueryBuilder()
            ->select('id', 'title', 'description', 'created_at')
            ->from('article')
            ->where('id = ?')
            ->setParameter(0, $vars["id"])
            ->executeQuery()
            ->fetchAssociative();
//no database izvelk datus
        $article = new Article($result['title'], $result['description'], $result['created_at'], $result['id']);
//ieliek datus Article

//        $errors= new Exception($article);
//        $errors->checkErrors();
        $articleLikes=Database::connect()
            ->createQueryBuilder()
            ->select("COUNT(id)")
            ->from("article_likes")
            ->where("article_id = ?")
            ->setParameter(0,(int)$vars["id"] )
            ->executeQuery()
        ->fetchOne();
//        var_dump($articleLikes);
        //make select query for article likes
        // select count(id) from article_likes where article_id =$vars[id]
        return new View("Articles/show.html", [
            "article" => $article,
            "articleLikes"=>$articleLikes
        ]);

    }


    public function create(): View
    {
//        var_dump($_SESSION);
        return new View("Articles/create.html", [
            "errors" => Errors::getAll(),
            "inputs"=>$_SESSION["inputs"] ?? []
            ]);
    }


    public function store(): Redirect
    {
        try {
            $validator = new ArticleFormValidator($_POST,[
                "title"=>["required","min:3"],
                "description"=>["required","min:10"]
            ]);
            $validator->passes();
        } catch (FormValidationException $e) {
            $_SESSION["errors"] = $validator->getErrors();
            $_SESSION["inputs"] = $_POST;
            return new Redirect("/articles/create");
        }
        Database::connect()
            ->insert('article', [
                'title' => $_POST['title'],
                'description' => $_POST['description']
            ]);


//redirect /articles
        return new Redirect("/articles");

//        var_dump($_POST);
//        new View("Articles/show.html");

    }

    public function delete(array $vars): Redirect
    {
        Database::connect()
            ->delete('article', [
                'id' => (int)$vars['id']
            ]);
        return new Redirect('/articles');
    }

    public function edit(array $vars): View
    {
        try {
            $connection = Database::connect();

            $result = $connection
                ->createQueryBuilder()
                ->select('id', 'title', 'description', 'created_at')
                ->from('article')
                ->where('id = ?')
                ->setParameter(0, $vars["id"])
                ->executeQuery()
                ->fetchAssociative();
            if ($result === false) {
                throw new ResourceNotFoundException("Article with id {$vars["id"]} not found");
//        return new View("404.html");
            } else {

                $article = new Article($result['title'], $result['description'], $result['created_at'], $result['id']);

                return new View("Articles/edit.html", [
                    'article' => $article
                ]);
            }
        } catch (ResourceNotFoundException $e) {
            var_dump($e->getMessage());
            return new View("404.html");
        }
    }

    public function update(array $vars): Redirect
    {
        Database::connect()
            ->update('article', [
                'title' => $_POST['title'],
                'description' => $_POST['description']
            ], ['id' => (int)$vars['id']
            ]);
        return new Redirect('/articles/' . $vars['id'] . "/edit");
    }
public function like(array $vars):Redirect
{
    //Make select query check if user already liked
    $articleId=(int)$vars['id'];
    Database::connect()
        ->insert('article_likes', [
            'article_id' => $articleId,
            'user_id' => 1 //$_SESSION
            ]);//user id

    return new Redirect('/articles/' . $articleId);
}
}















