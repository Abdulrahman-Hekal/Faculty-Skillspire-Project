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

    /**
     * Get user by ID.
     * 
     * @param int $id
     * @return array|null
     */
    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = ?";
        return $this->conn->execute_query($query, [$id])->fetch_assoc();
    }

    /**
     * Update user profile.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser($id, $data)
    {
        if (!empty($data['password'])) {
            $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
            return $this->conn->execute_query($query, [$data['name'], $data['email'], $data['password'], $id]);
        } else {
            $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
            return $this->conn->execute_query($query, [$data['name'], $data['email'], $id]);
        }
    }
}
