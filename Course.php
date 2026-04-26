<?php
class Course {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function getAll() {
        return $this->db->query(
            'SELECT c.*, s.label as semester_label, s.academic_year 
             FROM courses c 
             JOIN semesters s ON c.semester_id = s.id 
             ORDER BY c.created_at DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBySemester($semId) {
        $stmt = $this->db->prepare(
            'SELECT * FROM courses WHERE semester_id = ?'
        );
        $stmt->execute([$semId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($name, $credits, $semId) {
        $stmt = $this->db->prepare(
            'INSERT INTO courses (name, credits, semester_id) VALUES (?, ?, ?)'
        );
        $stmt->execute([$name, $credits, $semId]);
    }
    
    public function update($id, $name, $credits, $semId) {
        $stmt = $this->db->prepare(
            'UPDATE courses SET name = ?, credits = ?, semester_id = ? WHERE id = ?'
        );
        $stmt->execute([$name, $credits, $semId, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = ?');
        $stmt->execute([$id]);
    }
    
    public function countBySemester($semId) {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM courses WHERE semester_id = ?'
        );
        $stmt->execute([$semId]);
        return $stmt->fetchColumn();
    }
    
    public function countByCourse($id) {
        // تحقق من وجود grades مرتبطة
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM grades WHERE course_id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
}
?>