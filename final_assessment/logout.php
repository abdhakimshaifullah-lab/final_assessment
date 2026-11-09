<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !valid_csrf_token($_POST['csrf_token'] ?? null)) {
    http_response_code(400);
    exit('Invalid request.');
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();
header('Location: login.php');
exit;
