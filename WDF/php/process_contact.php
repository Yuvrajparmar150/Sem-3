<?php
/**
 * Practical 7: Process Contact Us Form Submissions
 * File: php/process_contact.php
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$name    = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$email   = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
$subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'General Inquiry');
$message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid name, email, and message.']);
    exit;
}

$dataDir = dirname(__DIR__) . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);

$jsonFile = $dataDir . '/contacts.json';
$csvFile  = $dataDir . '/contacts.csv';
$timestamp = date('Y-m-d H:i:s');
$msgId = 'MSG-' . rand(100, 999);

$record = [
    'id'        => $msgId,
    'name'      => $name,
    'email'     => $email,
    'subject'   => $subject,
    'message'   => $message,
    'timestamp' => $timestamp
];

// JSON Storage
$contacts = [];
if (file_exists($jsonFile)) {
    $contacts = json_decode(file_get_contents($jsonFile), true) ?? [];
}
array_unshift($contacts, $record);
file_put_contents($jsonFile, json_encode($contacts, JSON_PRETTY_PRINT), LOCK_EX);

// CSV Storage
$writeHeader = !file_exists($csvFile) || filesize($csvFile) === 0;
$fp = fopen($csvFile, 'a');
if ($fp) {
    flock($fp, LOCK_EX);
    if ($writeHeader) {
        fputcsv($fp, ['Message ID', 'Name', 'Email', 'Subject', 'Message', 'Timestamp']);
    }
    fputcsv($fp, [$msgId, $name, $email, $subject, $message, $timestamp]);
    flock($fp, LOCK_UN);
    fclose($fp);
}

echo json_encode([
    'status' => 'success',
    'message' => 'Thank you! Your message has been sent and saved to CSV/JSON storage.',
    'record' => $record
]);
exit;
