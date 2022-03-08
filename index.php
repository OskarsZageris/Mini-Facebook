<?php


require_once 'vendor/autoload.php';
use App\View;
use App\Controllers\usersController;
use Twig\Environment as EnvironmentAlias;
use Twig\Loader\FilesystemLoader as FilesystemLoaderAlias;




//$twig->addExtension(new \Twig\Extension\SandboxExtension());
session_start();
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', ['App\Controllers\usersController', 'index']);
    $r->addRoute('GET', '/users/{id:\d+}', ['App\Controllers\usersController', 'show']);
//Articles
    $r->addRoute('GET', '/articles', ['App\Controllers\ArticleController', 'index']);
    $r->addRoute('GET', '/articles/{id:\d+}', ['App\Controllers\ArticleController', 'show']);

    $r->addRoute('POST', '/articles', ['App\Controllers\ArticleController', 'store']);
    $r->addRoute('GET', '/articles/create', ['App\Controllers\ArticleController', 'create']);

    $r->addRoute('POST', '/articles/{id:\d+}/delete', ['App\Controllers\ArticleController', 'delete']);

    $r->addRoute('GET', '/articles/{id:\d+}/edit', ['App\Controllers\ArticleController', 'edit']);
    $r->addRoute('POST', '/articles/{id:\d+}', ['App\Controllers\ArticleController', 'update']);
//login
    $r->addRoute('GET', '/', ['App\Controllers\LoginController', 'index']);
    $r->addRoute('GET', '/users/login', ['App\Controllers\LoginController', 'getLogin']);
    $r->addRoute('POST', '/users/login', ['App\Controllers\LoginController', 'login']);
    $r->addRoute('POST', '/', ['App\Controllers\LoginController', 'logout']);

    $r->addRoute('GET', '/users/create', ['App\Controllers\usersController', 'createUser']);
    $r->addRoute('POST', '/users/create', ['App\Controllers\usersController', 'createNewUser']);


    $r->addRoute('POST', '/articles/{id:\d+}/like', ['App\Controllers\ArticleController', 'like']);
//    $r->addRoute('POST', '/signup', ['App\Controllers\signupController', 'signup']);

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        var_dump("404 not found");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        var_dump("405 Method Not Allowed");
        break;
    case FastRoute\Dispatcher::FOUND:
        $vars = $routeInfo[2];
    $route=$routeInfo[1][0];
    $method=$routeInfo[1][1];


/* @var View $response*/
      $response= (new $route)->$method($vars);
//var_dump($response);
        $loader = new FilesystemLoaderAlias('App/Views');
        $twig = new EnvironmentAlias($loader);
        $twig->addGlobal('session', $_SESSION);

if($response instanceof View){
        echo $twig->render($response->getPath(), $response->getVariables());
}
if($response instanceof \App\Redirect){
    header("Location: ".$response->getLocation());
    exit;
}

//      var_dump($controller);
        break;
}
if(isset($_SESSION["errors"])){
    unset ($_SESSION["errors"]);
}
if(isset($_SESSION["inputs"])){
    unset ($_SESSION["inputs"]);
}