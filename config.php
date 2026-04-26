<?php

// اتصال قاعدة البيانات
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=gpa_system;charset=utf8',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    return $pdo;
}

// تحقق من الدور
function requireRole($expected) {
    if (!isset($_SESSION['role']) || 
        time() - $_SESSION['last_activity'] > 1800) {
        session_destroy();
        header('Location: ?page=login');
        exit;
    }
    if ($_SESSION['role'] !== $expected) {
        http_response_code(403);
        die('Access Denied');
    }
    $_SESSION['last_activity'] = time();
}

// flash messages
function flash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}
?>