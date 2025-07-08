<?php

require_once __DIR__ . '/../config/config.php';

class Employee
{
    private $conn;
    private $table = 'employee';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($name, $email, $imagePath)
    {
        $sql = "INSERT INTO $this->table (name, email, profileImg, created_at, updated_at)
                VALUES (:name, :email, :profileImg, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':profileImg' => $imagePath
        ]);
    }

    public function update($id, $name, $email, $imagePath = null)
    {
        $sql = "UPDATE $this->table SET name = :name, email = :email, updated_at = NOW()";
        if ($imagePath) $sql .= ", profileImg = :profileImg";
        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $params = [
            ':name' => $name,
            ':email' => $email,
            ':id' => $id
        ];
        if ($imagePath) $params[':profileImg'] = $imagePath;

        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, profileImg, DATE_FORMAT(created_at, '%d %b %Y') AS created_at FROM $this->table WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($search = '', $sort = 'id', $order = 'DESC', $offset = 0, $limit = 10)
    {
        $query = "SELECT id, name, email, profileImg, DATE_FORMAT(created_at, '%d %b %Y') AS created_at
         FROM $this->table WHERE name LIKE :search OR email LIKE :search 
         OR DATE_FORMAT(created_at, '%d %b %Y') LIKE :search  ORDER BY $sort $order LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll($search = '')
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM $this->table WHERE name LIKE :search OR email LIKE :search");
        $stmt->execute([':search' => "%$search%"]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function checkIUnique($email, $id)
    {
        $sql = "SELECT id, name, email, profileImg, 
            DATE_FORMAT(created_at, '%d %b %Y') AS created_at 
            FROM $this->table";
        $params = [];

        if ($email && !$id) {
            $sql .= " WHERE email = :email";
            $params = [':email' => $email];
        }

        if ($id) {
            $sql .= " WHERE id != :id AND email = :email";
            $params = [':id' => $id, ':email' => $email];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
