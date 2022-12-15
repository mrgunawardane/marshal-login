<?php

namespace App\Models;

use PDO;
use PDOException;

class Post extends \Core\Model{
    public static function getAll(){

        try {
            // get the connection 
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts ORDER BY created_at');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}