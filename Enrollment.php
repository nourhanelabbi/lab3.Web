<?php
class Enrollment {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function getSemesterIds($studentId) {
        $stmt = $this->db->prepare(
            'SELECT semester_id FROM enrollments WHERE student_id = ?'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function create($studentId, $semId) {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO enrollments (student_id, semester_id) VALUES (?, ?)'
        );
        $stmt->execute([$studentId, $semId]);
    }
    
    public function delete($studentId, $semId) {
        $stmt = $this->db->prepare(
            'DELETE FROM enrollments WHERE student_id = ? AND semester_id = ?'
        );
        $stmt->execute([$studentId, $semId]);
    }
    
    public function exists($studentId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT id FROM enrollments WHERE student_id = ? AND semester_id = ?'
        );
        $stmt->execute([$studentId, $semId]);
        return $stmt->fetch() !== false;
    }
    
    public function getStudentsBySemester($semId) {
        $stmt = $this->db->prepare(
            'SELECT u.* FROM users u 
             JOIN enrollments e ON u.id = e.student_id 
             WHERE e.semester_id = ?'
        );
        $stmt->execute([$semId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getSemestersByStudent($studentId) {
        $stmt = $this->db->prepare(
            'SELECT s.* FROM semesters s 
             JOIN enrollments e ON s.id = e.semester_id 
             WHERE e.student_id = ?
             ORDER BY s.created_at DESC'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countByStudentSemester($studentId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM grades 
             WHERE student_id = ? AND semester_id = ?'
        );
        $stmt->execute([$studentId, $semId]);
        return $stmt->fetchColumn();
    }
}
?>