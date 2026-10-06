<?php
/**
 * Practical 6 & 7: REST API Endpoint Bridge
 * File: api.php
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$action = $_GET['action'] ?? '';
$dataDir = __DIR__ . '/data';

switch ($action) {
    case 'get_events':
        $file = $dataDir . '/events.json';
        echo file_exists($file) ? file_get_contents($file) : '[]';
        break;

    case 'get_students':
        $file = $dataDir . '/students.json';
        echo file_exists($file) ? file_get_contents($file) : '[]';
        break;

    case 'get_faqs':
        $file = $dataDir . '/faqs.json';
        echo file_exists($file) ? file_get_contents($file) : '[]';
        break;

    case 'get_notices':
        $file = $dataDir . '/notices.json';
        echo file_exists($file) ? file_get_contents($file) : '[]';
        break;

    case 'get_registrations':
        $file = $dataDir . '/registrations.json';
        echo file_exists($file) ? file_get_contents($file) : '[]';
        break;

    case 'get_locations':
        $file = $dataDir . '/locations.json';
        echo file_exists($file) ? file_get_contents($file) : '{}';
        break;

    default:
        echo json_encode([
            'status' => 'online',
            'portal' => 'StudentHub API',
            'developer' => 'Yuvraj Parmar (25DCE070)',
            'endpoints' => [
                '?action=get_events',
                '?action=get_students',
                '?action=get_faqs',
                '?action=get_notices',
                '?action=get_registrations',
                '?action=get_locations'
            ]
        ]);
        break;
}
exit;
