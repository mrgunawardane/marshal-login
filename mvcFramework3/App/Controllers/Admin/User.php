<?php

namespace App\Controllers\Admin;

class User extends \Core\Controller{

    protected function before(){
    }

    public function indexAction(){
        echo 'User admin index';
    }
}