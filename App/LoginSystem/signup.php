<?php
use App\Controllers\signupController;
use App\Database;
if(isset($_POST["submit"])) {
    //grabb the data
    $uid = $_POST["uid"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordRepeat"];
    $email = $_POST["email"];
//instantiate sign up contr class
    $signup = new signupController($uid, $password, $passwordRepeat, $email);
    var_dump($signup);
}
//    public function newUser(){
//    Database::connect()
//        ->insert('users',[
//            'uid' => $_POST['uid'],
//            'password'=> $_POST['password'],
//            'email'=> $_POST['email']
//        ]);
//    //running error handlers
//    //going back to front page
//
//}
