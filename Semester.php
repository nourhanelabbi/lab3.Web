<?php
class Semester {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function getAll() {
        return $this->db->query('SELECT * FROM semesters ORDER BY created_at DESC')
                        ->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActive() {
        $stmt = $this->db->prepare('SELECT * FROM semesters WHERE is_active = 1 LIMIT 1');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($label, $year) {
        $stmt = $this->db->prepare(
            'INSERT INTO semesters (label, academic_year) VALUES (?, ?)'
        );
        $stmt->execute([$label, $year]);
    }
    
    public function update($id, $label, $year) {
        $stmt = $this->db->prepare(
            'UPDATE semesters SET label = ?, academic_year = ? WHERE id = ?'
        );
        $stmt->execute([$label, $year, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM semesters WHERE id = ?');
        $stmt->execute([$id]);
    }
    
    public function setAllInactive() {
        $this->db->query('UPDATE semesters SET is_active = 0');
    }
    
    public function setActive($id) {
        $stmt = $this->db->prepare('UPDATE semesters SET is_active = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
}
?>