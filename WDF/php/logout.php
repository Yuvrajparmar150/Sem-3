<?php
/** Practical 10: secure logout (destroys session, clears remember-me token + cookie) */
require_once __DIR__ . '/layout.php';
start_session();
if (!empty($_SESSION['user'])) {
    $uid = (int)$_SESSION['user']['id'];
    audit('LOGOUT', 'User logged out', $uid);
    db()->prepare('UPDATE users SET remember_token_hash = NULL, remember_expires = NULL WHERE id = ?')->execute([$uid]);
}
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
setcookie('sh_remember', '', time() - 42000, '/');
session_destroy();
redirect('login.php?loggedout=1');
