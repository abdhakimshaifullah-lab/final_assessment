<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $is_https,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function require_login()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function valid_csrf_token($token)
{
    return isset($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

function ensure_users_table($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$conn->query($sql)) {
        die('Unable to initialize user accounts: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
    }
}
