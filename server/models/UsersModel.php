<?php

class UsersModel extends Model
{
    public function createUser($data)
    {
        $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        return $this->conn->execute_query($query, [
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        ]);
    }

    public function findUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        return $this->conn->execute_query($query, [$email])->fetch_assoc();
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = ?";
        return $this->conn->execute_query($query, [$id])->fetch_assoc();
    }
}
