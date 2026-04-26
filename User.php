<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function emailExists($email, $excludeId = null) {
        $sql = 'SELECT id FROM users WHERE email = ?';
        $params = [$email];
        if ($excludeId) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }
    
    public function create($name, $email, $password, $role) {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $password, $role]);
    }
    
    public function update($id, $name, $email) {
        $stmt = $this->db->prepare(
            'UPDATE users SET name = ?, email = ? WHERE id = ?'
        );
        $stmt->execute([$name, $email, $id]);
    }
    
    public function updatePassword($id, $hash) {
        $stmt = $this->db->prepare(
            'UPDATE users SET password = ? WHERE id = ?'
        );
        $stmt->execute([$hash, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
    
    public function getAllByRole($role) {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE role = ? ORDER BY name'
        );
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>