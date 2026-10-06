<?php
/**
 * Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage
 * File: php/process_register.php
 * Student: Yuvraj Parmar (25DCE070) - CHARUSAT FTE
 */

header('Content-Type: application/json; charset=utf-8');

// Ensure Request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid Request Method. Only POST is accepted.'
    ]);
    exit;
}

// 1. Sanitize Inputs
$name      = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$studentId = trim(filter_input(INPUT_POST, 'studentId', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$email     = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
$mobile    = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$course    = trim(filter_input(INPUT_POST, 'course', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$year      = trim(filter_input(INPUT_POST, 'year', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '2nd Year');
$gender    = trim(filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'Not specified');
$country   = trim(filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'India');
$state     = trim(filter_input(INPUT_POST, 'state', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$city      = trim(filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$password  = $_POST['password'] ?? '';

// 2. Server-Side Validation
$errors = [];

// Name validation (3-50 chars, alphabets and spaces)
if (empty($name) || !preg_match("/^[a-zA-Z\s]{3,50}$/", $name)) {
    $errors['name'] = 'Name must be 3-50 characters long and contain only letters and spaces.';
}

// Student ID validation
if (empty($studentId) || !preg_match("/^[0-9]{2}[A-Za-z]{2,4}[0-9]{3}$/", $studentId)) {
    $errors['studentId'] = 'Enter a valid student enrollment number (e.g. 25DCE070).';
}

// Email validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'A valid email address is required.';
}

// Mobile validation (10 digits starting 6-9)
if (empty($mobile) || !preg_match("/^[6-9]\d{9}$/", $mobile)) {
    $errors['mobile'] = 'A valid 10-digit Indian mobile number is required.';
}

// Course validation
if (empty($course)) {
    $errors['course'] = 'Please select a course/department.';
}

// Return errors if any
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server-side validation failed.',
        'errors' => $errors
    ]);
    exit;
}

// 3. Prepare Storage Files in ../data
$dataDir = dirname(__DIR__) . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$jsonFile = $dataDir . '/registrations.json';
$csvFile  = $dataDir . '/registrations.csv';

$timestamp = date('Y-m-d H:i:s');
$registrationId = 'REG-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

$newRecord = [
    'id'           => $registrationId,
    'name'         => $name,
    'studentId'    => $studentId,
    'email'        => $email,
    'mobile'       => $mobile,
    'course'       => $course,
    'year'         => $year,
    'gender'       => $gender,
    'country'      => $country,
    'state'        => $state,
    'city'         => $city,
    'registeredAt' => $timestamp,
    'status'       => 'Verified'
];

// --- 4. JSON File Storage (with Lock) ---
$registrations = [];
if (file_exists($jsonFile)) {
    $content = file_get_contents($jsonFile);
    $registrations = json_decode($content, true) ?? [];
}

// Prepend new record
array_unshift($registrations, $newRecord);
file_put_contents($jsonFile, json_encode($registrations, JSON_PRETTY_PRINT), LOCK_EX);

// --- 5. CSV File Storage (with Lock) ---
$writeHeader = !file_exists($csvFile) || filesize($csvFile) === 0;
$fp = fopen($csvFile, 'a');
if ($fp) {
    flock($fp, LOCK_EX);
    if ($writeHeader) {
        fputcsv($fp, ['Registration ID', 'Full Name', 'Student ID', 'Email', 'Mobile', 'Course', 'Year', 'Gender', 'Country', 'State', 'City', 'Timestamp', 'Status']);
    }
    fputcsv($fp, [
        $registrationId,
        $name,
        $studentId,
        $email,
        $mobile,
        $course,
        $year,
        $gender,
        $country,
        $state,
        $city,
        $timestamp,
        'Verified'
    ]);
    flock($fp, LOCK_UN);
    fclose($fp);
}

// 6. Return Response
echo json_encode([
    'status' => 'success',
    'message' => 'Student registration completed successfully. Record stored in JSON and CSV storage.',
    'record' => $newRecord
]);
exit;
