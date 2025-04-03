<?php

namespace App\Models;

use Database;
use PDO;

class UserModel
{
    public static function getAll()
    {
        $stmt = Database::query("SELECT id, name, email FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function find($id)
    {
        $stmt = Database::query("SELECT id, name, email FROM users WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function create($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        Database::query($sql, [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword
        ]);

        return Database::getInstance()->lastInsertId();
    }

    public static function findByEmail($email)
    {
        $stmt = Database::query("SELECT id, name, email, password FROM users WHERE email = :email", ['email' => $email]);
        return $stmt->fetch();
    }
}
