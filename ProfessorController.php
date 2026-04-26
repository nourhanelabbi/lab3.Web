<?php
require_once 'models/User.php';
require_once 'models/Semester.php';
require_once 'models/Assignment.php';

class ProfessorController {
    public function __construct() {
        requireRole('professor');
        $page = $_GET['page'] ?? 'professor.grades';
        
        match($page) {
            'professor.grades' => $this->grades(),
            default            => $this->grades()
        };
    }
    
    private function grades() {
    $profId      = $_SESSION['user_id'];
    $assignments = (new Assignment())->getByProfessor($profId);
    
    // جيب السيمسترات مباشرة من assignments
    $semesters = [];
    foreach ($assignments as $a) {
        $semesters[$a['semester_id']] = [
            'id'            => $a['semester_id'],
            'label'         => $a['semester_label'],
            'academic_year' => $a['academic_year']
        ];
    }
    
    include BASE_PATH . 'views/professor/grades.php';
}
}
?>