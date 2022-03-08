<?php

namespace App\Controllers;
use Twig\Loader\FilesystemLoader;
use App\Database;
use App\Redirect;
use App\View;
use Doctrine\DBAL\Exception;
use App\Models\User;

class LoginController
{
//---MAIN VIEW---
    public function index(): View
    {
//        var_dump($_SESSION);

        return new View('Main/index.html');
    }

    public function getLogin(): View
    {
        return new View('Users/login.html');
    }

    public function login():Redirect{
        $userEmail = $_POST['email'];
        $userPassword = $_POST['password'];

        $connection = Database::connect();

        $results = $connection
            ->createQueryBuilder()
            ->select('email', 'password',"id","uid","created_at")
            ->from('users')
            ->orderBy('created_at', 'desc')
            ->executeQuery()
            ->fetchAllAssociative();
//        var_dump($results);
foreach ($results as $result){
    if($result["password"]==$userPassword&&$result["email"]==$userEmail){

//            session_start();
       $_SESSION["userid"]=$result["id"];
       $_SESSION["username"]=$result["uid"];
//       $user= new User($result["email"],$result["password"],$result["created_at"]);
//        var_dump($user);
//;
    }
}
        return new Redirect('/');
//var_dump($_POST);
    }
        public function logout(): Redirect
        {
            $result = null;
            if(isset($_SESSION['userid'])){

                unset($_SESSION['username']);
                unset($_SESSION['userid']);
                session_destroy();

                $result = new Redirect('/');
            }

            return $result;
        }

}

