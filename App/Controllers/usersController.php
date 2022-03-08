<?php

namespace App\Controllers;
use App\Models\Signup;
use App\Redirect;
use App\View;
use App\Database;

class usersController{
    public function index():View //RESTful API
    {
        //get information from database
        $connection = Database::connect();

        $results = $connection
            ->createQueryBuilder()
            ->select('id', 'uid', 'email', 'password')
            ->from('users')
//            ->orderBy('created_at', 'desc')
            ->executeQuery()
            ->fetchAllAssociative();
//        var_dump($results);

        foreach ($results as $result) {
            $users[] = new Signup($result['uid'], $result['password'], $result['id'], $result['email']);
        }
        return new View("Users/index.html", [
            'users' => $users
        ]);

        //creat array wi8th Article objects
//        return new View("Users/index.html",[
//            'articles'=>[]
//        ]);
    }
    public function show(array $vars)
    {
        //$vars['id']
        //get information from database where article ID = $vars["id"]
        //creat article object
        //give template for rendering


//echo 123;
        //$_POST
        //$_GET
        //darbības


//        var_dump($vars);
        return new View("Users/show.html",['id'=>$vars["id"]]);
    }

    public function createUser():View{

        return new View("Users/create.html");
    }
    public function createNewUser():Redirect{
        var_dump($_POST);
        Database::connect()
            ->insert('users',[
                'uid' => $_POST['uid'],
                'email'=> $_POST['email'],
                'password'=> $_POST['password']
            ]);
//redirect /articles
        return new Redirect("/users");
    }

}