<?php
require_once 'models/Semester.php';
require_once 'models/Course.php';
require_once 'models/Grade.php';
require_once 'models/GPA.php';
require_once 'models/Enrollment.php';

class StudentController {
    public function __construct() {
        requireRole('student');
        $page = $_GET['page'] ?? 'student.dashboard';
        
        match($page) {
            'student.dashboard' => $this->dashboard(),
            'student.history'   => $this->history(),
            default             => $this->dashboard()
        };
    }
    
    private function dashboard() {
        include BASE_PATH . 'views/student/dashboard.php';
    }
    
    private function history() {
        include BASE_PATH . 'views/student/history.php';
    }
}
?>