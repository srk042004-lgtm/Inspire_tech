<?php
// Secure session initialization
if (session_status() === PHP_SESSION_NONE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();

    if (!isset($_SESSION['__initiated'])) {
        session_regenerate_id(true);
        $_SESSION['__initiated'] = true;
    }
}
