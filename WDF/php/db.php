<?php
/**
 * Practical 8: Secure database connection (PDO + prepared statements)
 * File: php/db.php
 * - Errors are logged, never shown to the visitor.
 * - Emulated prepares are OFF so real server-side prepared statements are used.
 */
function db_config(): array
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require __DIR__ . '/config.php';
    }
    return $cfg;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $c = db_config();
    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        if ($c['driver'] === 'sqlite') {
            $pdo = new PDO('sqlite:' . $c['sqlite'], null, null, $opts);
            $pdo->exec('PRAGMA foreign_keys = ON');
        } else {
            $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['dbname']};charset=utf8mb4";
            $pdo = new PDO($dsn, $c['username'], $c['password'], $opts);
        }
    } catch (PDOException $e) {
        error_log('[StudentHub] DB connection failed: ' . $e->getMessage());
        http_response_code(500);
        exit('Database connection failed. Please try again later.');
    }
    return $pdo;
}

function db_driver(): string
{
    return db_config()['driver'];
}

/** Month bucket expression that works in MySQL and SQLite. */
function sql_month(string $col): string
{
    return db_driver() === 'sqlite' ? "strftime('%Y-%m', $col)" : "DATE_FORMAT($col, '%Y-%m')";
}
