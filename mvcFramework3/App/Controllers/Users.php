<?php

namespace App\Controllers;

use \Core\View;
use Core;
use App\Models\User;

// header('Content-Type: application/json');

class Users extends \Core\Controller{

    private $_db;
    public function signupAction(){

        // header('Content-Type: application/json');

        View::render('User/signup.html', [
            'successMessage' => 'Register successfully'
        ]);
    }
    public function registerAction(){

        $validator = new Core\Validator();
        $validation = $validator->check($_POST, array(
            'firstName' => array(
                'required' => true,
                'min'=> 3,
                'max' => 20
            ),
            'lastName' => array(
                'required' => true,
                'min'=> 3,
                'max' => 20
            ),
            'email' => array(
                'required' => true,
                'isEmail' => true,
                // 'unique' => 'users',
                'max' => 50
            ),
            'password' => array(
                'required' => true,
                'contain_letter' => true,
                'contain_number' => true,
                'min'=> 8
            ),
            'repeatpassword' => array(
                'required' => true,
                'matches' => 'password'
            ),

        ));


        if($validation->passed()){
            $hashPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $successed = User::createUser(array(
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
                'email' => $_POST['email'],
                'password' => $hashPassword
            ));

            if($successed){

                $verifyCode = rand(1,1000000);

                $updated = User::updateUser(
                    array(
                        'key' => 'verifyCode',
                        'value' => $verifyCode,
                        'email' => $_POST['email']
                    )
                );

                if($updated){
                    // *** To Email ***
                    $to = $_POST['email'];
                    $subject = 'Verify Your Email';

                    // *** Content Email ***
                    $content = 'Verify Code : ' . $verifyCode;
                    //*** Head Email ***
                    $headers = "From: mrgunawardane@gmail.com\r\n";

                    try {
                        mail($to, $subject, $content, $headers);
                    } catch (\Throwable $th) {
                        echo $th;
                    }

                    View::render('User/verifyEmail.html', array(
                        'email' => $_POST['email']
                    ));
                }
                
            }else{
                View::render('User/signup.html',[
                    'errors' => array('Email is already exists'),
                    'values' => $_POST
                ]);
            }

        }else{

            View::render('User/signup.html',[
                'errors' => $validation->errors(),
                'values' => $_POST
            ]);
        }
    }
 
    public function signupSuccessAction(){
        View::render('User/signupSuccess.html');
    }

    public function verifyEmailAction(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $user = User::findUser($_POST['email']);

            if($_POST['verifyCode'] == $user['verifyCode']){

                $updated = User::updateUser(
                    array(
                        'key' => 'verified',
                        'value' => 1,
                        'email' => $_POST['email']
                    )
                );
                if($updated){
                    View::render('User/login.html');
                }
            }else{
                View::render('User/verifyEmail.html', array(
                    'email' => $_POST['email'],
                    'error' => 'Incorrect verification code.'
                ));
            }
        }
    }

    public function loginAction(){
        View::render('User/login.html');
    }

    public function log(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $existedUser = User::findUser($_POST['email'], $_POST['passwd']);
            if($existedUser){
                if($existedUser['verified']){
                    session_start();

                    session_regenerate_id();

                    $_SESSION['user_id'] = $existedUser['id'];

                    // set cookie
                    setcookie('myCookie', $existedUser['id'], time()+84600, '/');
                    header('Location: http://localhost/mvcFramework3/public/projects/dashboard');
                    exit;
                    
                }else{
                    View::render('User/verifyEmail.html', array(
                        'email' => $_POST['email'],
                        'error' => 'Please verify your email.'
                    ));
                }

            }else{
                echo 'User is not here';
            }
        }
    }

    public function logoutAction(){
        session_start();
        session_destroy();

        header('Location: http://localhost/mvcFramework3/public/');
        exit;
    }

    protected function before(){
        // echo '<br> before <br>';
    }
    protected function after(){
        
    }
}