<?php
class Assignment {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function create($profId, $courseId, $semId) {
        $stmt = $this->db->prepare(
            'INSERT INTO assignments (professor_id, course_id, semester_id) 
             VALUES (?, ?, ?)'
        );
        $stmt->execute([$profId, $courseId, $semId]);
    }
    
    public function courseAlreadyAssigned($courseId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT id FROM assignments 
             WHERE course_id = ? AND semester_id = ?'
        );
        $stmt->execute([$courseId, $semId]);
        return $stmt->fetch() !== false;
    }
    
    public function exists($profId, $courseId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT id FROM assignments 
             WHERE professor_id = ? AND course_id = ? AND semester_id = ?'
        );
        $stmt->execute([$profId, $courseId, $semId]);
        return $stmt->fetch() !== false;
    }
    
    public function getByProfessor($profId) {
        $stmt = $this->db->prepare(
            'SELECT a.*, c.name as course_name, s.label as semester_label,
                    s.academic_year
             FROM assignments a
             JOIN courses c ON a.course_id = c.id
             JOIN semesters s ON a.semester_id = s.id
             WHERE a.professor_id = ?'
        );
        $stmt->execute([$profId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        return $this->db->query(
            'SELECT a.*, u.name as prof_name, c.name as course_name,
                    s.label as semester_label, s.academic_year
             FROM assignments a
             JOIN users u ON a.professor_id = u.id
             JOIN courses c ON a.course_id = c.id
             JOIN semesters s ON a.semester_id = s.id'
        )->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM assignments WHERE id = ?');
        $stmt->execute([$id]);
    }
}
?>