<?php

namespace App\Controllers;

use \Core\View;
use App\Models\User;

session_start();

class Home extends \Core\Controller{
    public function indexAction(){
        $name = 'User';
        $isLogged = false;
        
        if($_SESSION !== array()){

            $user = User::findUser($_SESSION['user_id']);
            $name = $user['firstName'] . ' ' . $user['lastName'];
            $isLogged = true;
        }

        View::render('Home/index.html', [
            'name' => $name,
            'isLogged' => $isLogged,
        ]);
    }

    protected function before(){
        // echo '<br> before <br>';
    }
    protected function after(){
        // echo '<br> after <br>';
    }
}