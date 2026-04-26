<?php
session_start();
require_once 'config.php';
define('BASE_PATH', __DIR__ . '/'); 
$page = $_GET['page'] ?? 'login';

if (str_starts_with($page, 'admin.')) {
    require_once 'controllers/AdminController.php';
    $ctrl = new AdminController();
} elseif (str_starts_with($page, 'professor.')) {
    require_once 'controllers/ProfessorController.php';
    $ctrl = new ProfessorController();
} elseif (str_starts_with($page, 'student.')) {
    require_once 'controllers/StudentController.php';
    $ctrl = new StudentController();
} elseif ($page === 'login' || $page === 'logout') {
    require_once 'controllers/AuthController.php';
    $ctrl = new AuthController();
} else {
    header('Location: ?page=login');
    exit;
}
?>