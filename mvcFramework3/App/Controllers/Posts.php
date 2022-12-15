<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Post;

class Posts extends \Core\Controller{
    
    public function indexAction(){
        $posts = Post::getAll();

        View::render('Posts/index.html', [
            'posts' => $posts
        ]);
    }

    public function addNewAction(){
        echo 'add new function in post';
    }

    public function editAction(){
        echo 'edit function in post';
        echo '<pre>' . htmlspecialchars(print_r($this->route_params, true)) . '</pre>';
    }

}