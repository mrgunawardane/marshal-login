<?php

namespace App\Models;

use PDO;
use PDOException;

class Project extends \Core\Model{

    public static function createProject($fields = array()){
        $db = null;
        try {
            // get the connection 
            $db = static::getDB();
            $sql = 'INSERT INTO projects (projectName, description, field, leaderId) VALUES (:projectName,:description,:field,:leaderId)';

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':projectName', $fields['projectName'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $fields['description'], PDO::PARAM_STR);
            $stmt->bindValue(':field', $fields['field'], PDO::PARAM_STR);
            $stmt->bindValue(':leaderId', $fields['leaderId'], PDO::PARAM_INT);

            $stmt->execute();
            // $stmt->closeCursor();

            $stmt = null;
            $sql = 'SELECT * FROM projects WHERE projectName = :projectName';
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':projectName', $fields['projectName'], PDO::PARAM_STR);
            $stmt->execute();
            $project = $stmt->fetch();
            $stmt->closeCursor();
            return $project['id'];

        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }
    }

    public static function insertProjectMember($fields = array()){
        // try {

        //     $stmt = null;

        //     $db = static::getDB();
        //     $sql = 'INSERT INTO projectmembers (projectId, userId) VALUES (:projectId,:userId)';

        //     $stmt = $db->prepare($sql);

        //     $stmt->bindValue(':projectId', $fields['projectId'], PDO::PARAM_INT);
        //     $stmt->bindValue(':userId', $fields['userId'], PDO::PARAM_INT);

        //     $stmt->execute();
        //     return true;
        // } catch (PDOException $e) {
        //     return false;
        // }

        $db = null;
        try {
            // $db = static::getDB();
            $db = mysqli_connect('localhost', 'root', '', 'mvc');
            // updated data come from array as key values 
            // $sql = 'UPDATE users SET ' . $fields['key'] . ' =:value WHERE email=:email';
            $sql = 'INSERT INTO projectmembers (projectId, userId) VALUES ('. $fields['projectId'] . ', ' .$fields['userId'].')';
            // $sql = 'UPDATE users SET ' .$fields['key'] . ' = ' .$fields['value'] . ' WHERE email = ' . '"' . $fields['email'] . '"';

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


    public static function getAllProjects($userId){
        $db = null;
        try {
            // get the connection 
            $db = static::getDB();
            $sql = 'SELECT * FROM projects WHERE id IN(SELECT projectId FROM projectmembers WHERE userId=:userId)';
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            // $stmt->bindValue(':description', $fields['description'], PDO::PARAM_STR);
            // $stmt->bindValue(':field', $fields['field'], PDO::PARAM_STR);
            // $stmt->bindValue(':leaderId', $fields['leaderId'], PDO::PARAM_INT);

            $stmt->execute();
            $data = $stmt->fetchAll();

            // $stmt->closeCursor();

            return $data;

        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }
    }
}