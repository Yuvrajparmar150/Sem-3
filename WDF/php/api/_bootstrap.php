<?php
/**
 * Practical 13: shared API bootstrap - JSON headers, centralized error handling, auth + CSRF checks
 */
require_once dirname(__DIR__) . '/layout.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

set_exception_handler(function (Throwable $t) {
    error_log('[StudentHub API] ' . $t->getMessage());
    json_out(['status' => 'error', 'message' => 'Internal server error'], 500);
});

/** Decode JSON body (or fall back to form fields). */
function api_input(): array
{
    $raw = file_get_contents('php://input');
    if ($raw !== '' && $raw !== false) {
        $j = json_decode($raw, true);
        if (!is_array($j)) { json_out(['status' => 'error', 'message' => 'Malformed JSON body'], 400); }
        return $j;
    }
    return $_POST;
}
/** Writes: admin only + CSRF header. */
function api_guard_write(): array
{
    $u = require_login(['admin'], true);
    $tok = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!csrf_valid($tok)) { json_out(['status' => 'error', 'message' => 'Invalid CSRF token'], 403); }
    return $u;
}
