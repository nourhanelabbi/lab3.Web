<?php
class GPA {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function recompute($studentId, $semId) {
        $grade = new Grade();
        $rows  = $grade->getAllWithCredits($studentId, $semId);
        
        $totalPoints  = 0;
        $totalCredits = 0;
        
        foreach ($rows as $row) {
            $totalPoints  += floatval($row['grade']) * intval($row['credits']);
            $totalCredits += $row['credits'];
        }
        
        if ($totalCredits > 0) {
            $gpa = round($totalPoints / $totalCredits, 2);
            $this->upsert($studentId, $semId, $gpa);
        }
    }
    
    public function upsert($studentId, $semId, $gpa) {
        $stmt = $this->db->prepare(
            'INSERT INTO gpa_records (student_id, semester_id, gpa)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE gpa = VALUES(gpa)'
        );
        $stmt->execute([$studentId, $semId, $gpa]);
    }
    
    public function get($studentId, $semId) {
        $stmt = $this->db->prepare(
            'SELECT * FROM gpa_records 
             WHERE student_id = ? AND semester_id = ?'
        );
        $stmt->execute([$studentId, $semId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAllByStudent($studentId) {
        $stmt = $this->db->prepare(
            'SELECT g.*, s.label, s.academic_year 
             FROM gpa_records g
             JOIN semesters s ON g.semester_id = s.id
             WHERE g.student_id = ?
             ORDER BY s.created_at'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>