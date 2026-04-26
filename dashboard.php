<?php
class Grade {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function get($studentId, $courseId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT grade FROM grades 
             WHERE student_id = ? AND course_id = ? AND semester_id = ?'
        );
        $stmt->execute([$studentId, $courseId, $semId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['grade'] : null;
    }
    
    public function upsert($studentId, $courseId, $semId, $profId, $grade) {
        $stmt = $this->db->prepare(
            'INSERT INTO grades (student_id, course_id, semester_id, professor_id, grade)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE grade = VALUES(grade)'
        );
        $stmt->execute([$studentId, $courseId, $semId, $profId, $grade]);
    }
    
    public function getAllWithCredits($studentId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT g.grade, c.credits 
             FROM grades g
             JOIN courses c ON g.course_id = c.id
             WHERE g.student_id = ? AND g.semester_id = ?'
        );
        $stmt->execute([$studentId, $semId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countByCourse($courseId) {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM grades WHERE course_id = ?'
        );
        $stmt->execute([$courseId]);
        return $stmt->fetchColumn();
    }
    
    public function countByStudentSemester($studentId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM grades 
             WHERE student_id = ? AND semester_id = ?'
        );
        $stmt->execute([$studentId, $semId]);
        return $stmt->fetchColumn();
    }
    
    public function getAllWithDetailsByStudent($studentId) {
        $stmt = $this->db->prepare(
            'SELECT s.label as semester_label, s.academic_year,
                    c.name as course_name, c.credits,
                    g.grade
             FROM grades g
             JOIN courses c ON g.course_id = c.id
             JOIN semesters s ON g.semester_id = s.id
             WHERE g.student_id = ?
             ORDER BY s.created_at, c.name'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>