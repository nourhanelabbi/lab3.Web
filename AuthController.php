<?php
require_once 'models/User.php';

class AuthController {
    public function __construct() {
        $page = $_GET['page'] ?? 'login';
        
        if ($page === 'logout') {
            $this->logout();
        } else {
            $this->login();
        }
    }
    
    private function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = htmlspecialchars(trim($_POST['email']));
            $password = $_POST['password'];
            
            $user = (new User())->findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['role']          = $user['role'];
                $_SESSION['name']          = $user['name'];
                $_SESSION['last_activity'] = time();
                
                match($user['role']) {
                    'admin'     => header('Location: ?page=admin.dashboard'),
                    'professor' => header('Location: ?page=professor.grades'),
                    'student'   => header('Location: ?page=student.dashboard'),
                };
                exit;
            } else {
                flash('danger', 'Email ou mot de passe incorrect');
                header('Location: ?page=login');
                exit;
            }
        }
        
        include 'views/login.php';
    }
    
    private function logout() {
        session_destroy();
        header('Location: ?page=login');
        exit;
    }
}
?>