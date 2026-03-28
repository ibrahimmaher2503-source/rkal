<?php
// Harden session cookie settings
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', '1');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}
session_start();

// 30-minute idle timeout
$timeout = 30 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();

// Check auth
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Force password change redirect
if (!empty($_SESSION['must_change_password']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: login.php?change_password=1');
    exit;
}
