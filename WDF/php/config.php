<?php
/**
 * Practical 8: Database configuration
 * File: php/config.php
 * Default driver is MySQL (XAMPP). For the sandbox demo set STUDENTHUB_DRIVER=sqlite.
 */
return [
    'driver'   => getenv('STUDENTHUB_DRIVER') ?: 'mysql',
    'host'     => getenv('DB_HOST') ?: '127.0.0.1',
    'port'     => getenv('DB_PORT') ?: '3306',
    'dbname'   => getenv('DB_NAME') ?: 'studenthub',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',   // XAMPP default: empty
    'sqlite'   => dirname(__DIR__) . '/data/studenthub.sqlite',
    // Practical 10: idle timeout in seconds
    'session_timeout' => (int)(getenv('STUDENTHUB_TIMEOUT') ?: 900),
];
