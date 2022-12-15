<?php

namespace App\Models;

use PDO;
use PDOException;

class User extends \Core\Model{
    public static function createUser($fields = array()){
        $db = null;
        try {
            // get the connection 
            $db = static::getDB();
            $sql = 'INSERT INTO users (firstName, lastName, email, passwd) VALUES (:firstName,:lastName,:email,:passwd)';

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':firstName', $fields['firstName'], PDO::PARAM_STR);
            $stmt->bindValue(':lastName', $fields['lastName'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $fields['email'], PDO::PARAM_STR);
            $stmt->bindValue(':passwd', $fields['password'], PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();

            return true;

        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }
    }

    public static function updateUser($fields = array()){
        $db = null;
        try {
            // $db = static::getDB();
            $db = mysqli_connect('localhost', 'root', '', 'mvc');
            // updated data come from array as key values 
            // $sql = 'UPDATE users SET ' . $fields['key'] . ' =:value WHERE email=:email';

            $sql = 'UPDATE users SET ' .$fields['key'] . ' = ' .$fields['value'] . ' WHERE email = ' . '"' . $fields['email'] . '"';

            // $stmt = $db->prepare($sql);

            // $stmt->bindValue(':value', $fields['value'], PDO::PARAM_STR);
            // $stmt->bindValue(':email', $fields['email'], PDO::PARAM_STR);

            // $stmt->execute();
            mysqli_query($db, $sql);

            return true;
        } catch (PDOException $e) {
            //throw $th;
            echo $e;
            return false;
        }
    }

    public static function findUser($id, $password = NULL){
        try {
            $user = null;
            $db = static::getDB();
            
            if(is_numeric($id)){
                $sql = 'SELECT * FROM users WHERE id = ?';
            }else{
                $sql = 'SELECT * FROM users WHERE email = ?';
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);

            $user = $stmt->fetch();

            if($user == null){
                echo 'no user';
                return false;
            }else{
                // if password also is given then check it as well
                if($password){
                
                    if(password_verify($password, $user['passwd'])){
                        return $user;
                    }else{
                        return false;
                    }
                }else{
                    return $user;
                }
            }

        } catch (PDOException $e) {
            // echo $e->getMessage();
        }
    }

}