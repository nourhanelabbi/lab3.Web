<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/GPA.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Semester.php';
require_once __DIR__ . '/../models/Enrollment.php';

header('Content-Type: application/json');

requireRole('student');

$studentId = $_SESSION['user_id'];
$action    = $_GET['action'] ?? '';

switch ($action) {

    case 'current':
        $semester = (new Semester())->getActive();
        if (!$semester) {
            echo json_encode(['error' => 'Pas de semestre actif']);
            exit;
        }
        if (!(new Enrollment())->exists($studentId, $semester['id'])) {
            echo json_encode(['error' => 'Non inscrit']);
            exit;
        }
        $courses = (new Course())->getBySemester($semester['id']);
        $grade   = new Grade();
        foreach ($courses as &$c) {
            $c['grade']        = $grade->get($studentId, $c['id'], $semester['id']);
            $c['grade_points'] = ($c['grade'] ?? 0) * $c['credits'];
        }
        $gpaRecord = (new GPA())->get($studentId, $semester['id']);
        $gpa       = $gpaRecord ? $gpaRecord['gpa'] : null;
        echo json_encode([
            'semester' => $semester,
            'courses'  => $courses,
            'gpa'      => $gpa
        ]);
        break;

    case 'history':
        $semesters = (new Enrollment())->getSemestersByStudent($studentId);
        $grade     = new Grade();
        foreach ($semesters as &$sem) {
            $sem['courses'] = (new Course())->getBySemester($sem['id']);
            foreach ($sem['courses'] as &$c) {
                $c['grade']        = $grade->get($studentId, $c['id'], $sem['id']);
                $c['grade_points'] = ($c['grade'] ?? 0) * $c['credits'];
            }
            $gpaRecord  = (new GPA())->get($studentId, $sem['id']);
            $sem['gpa'] = $gpaRecord ? $gpaRecord['gpa'] : null;
        }
        echo json_encode($semesters);
        break;

    case 'export':
        requireRole('student');
        $rows = (new Grade())->getAllWithDetailsByStudent($studentId);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="gpa_history.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Semestre','Année','Cours','Crédits','Note','Points']);
        foreach ($rows as $row) {
            fputcsv($out, [
                $row['semester_label'],
                $row['academic_year'],
                $row['course_name'],
                $row['credits'],
                $row['grade'],
                $row['grade'] * $row['credits']
            ]);
        }
        fclose($out);
        exit;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Action invalide']);
}
?>