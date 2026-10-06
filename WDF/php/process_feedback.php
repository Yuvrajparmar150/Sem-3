<?php
/**
 * Practical 7: Process Feedback and Ratings
 * File: php/process_feedback.php
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$name     = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'Anonymous');
$email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
$category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'General');
$rating   = intval($_POST['rating'] ?? 5);
$comments = trim(filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

if (empty($comments)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Feedback comments cannot be empty.']);
    exit;
}

$dataDir = dirname(__DIR__) . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);

$jsonFile = $dataDir . '/feedbacks.json';
$csvFile  = $dataDir . '/feedbacks.csv';
$timestamp = date('Y-m-d H:i:s');
$fbId = 'FB-' . rand(100, 999);

$record = [
    'id'        => $fbId,
    'name'      => $name,
    'email'     => $email,
    'category'  => $category,
    'rating'    => $rating,
    'comments'  => $comments,
    'timestamp' => $timestamp
];

// JSON Storage
$feedbacks = [];
if (file_exists($jsonFile)) {
    $feedbacks = json_decode(file_get_contents($jsonFile), true) ?? [];
}
array_unshift($feedbacks, $record);
file_put_contents($jsonFile, json_encode($feedbacks, JSON_PRETTY_PRINT), LOCK_EX);

// CSV Storage
$writeHeader = !file_exists($csvFile) || filesize($csvFile) === 0;
$fp = fopen($csvFile, 'a');
if ($fp) {
    flock($fp, LOCK_EX);
    if ($writeHeader) {
        fputcsv($fp, ['Feedback ID', 'Name', 'Email', 'Category', 'Rating', 'Comments', 'Timestamp']);
    }
    fputcsv($fp, [$fbId, $name, $email, $category, $rating, $comments, $timestamp]);
    flock($fp, LOCK_UN);
    fclose($fp);
}

echo json_encode([
    'status' => 'success',
    'message' => 'Thank you! Your feedback has been stored successfully.',
    'record' => $record
]);
exit;
