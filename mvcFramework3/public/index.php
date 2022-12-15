<?php

ini_set('session.cookie_lifetime', '864000');

// autoloader 
spl_autoload_register(function ($class) {

    // get the parent directory 
    $root = dirname(__DIR__);
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';

    if(is_readable($file)){
        require $file;
    }
});

$router = new Core\Router();

/* *** routes format***

Route           Controller  Action

'/'             Home        index
'/posts'        Posts       index
'/show_post'    Posts       show
*/

/*
    URLs                                format                  Controller      Action

    marshal/project/index                                       project         index
    marshal/project/new                                         project         new
    marshal/user/index          '{controller} / {action}'       user            index
    marshal/user/new                                            user            new
    marshal/Admin/index                                         Admin           index
*/

// add routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
// $router->add('posts', ['controller' => 'posts', 'action' => 'index']);
//$router->add('posts/new', ['controller' => 'posts', 'action' => 'new']);
$router->add('{controller}/{action}');
// $router->add('admin/{action}/{controller}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

// match requested route 
$url = $_SERVER['QUERY_STRING'];

// if($router->match($url)){
//     echo '<pre>';
//     // var_dump($router->getParams());
//     echo htmlspecialchars(print_r($router->getRoutes(), true));
//     echo htmlspecialchars(print_r($router->getParams(), true));
//     echo '</pre>';
// }else{
//     echo "Not found " . $url;
// }

$router->dispatch($url);