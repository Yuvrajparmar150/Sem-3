<?php
/**
 * Practical 13: Students JSON API
 *   GET  students_api.php?q=&page=&per=   -> list (search + pagination)
 *   GET  students_api.php?id=5            -> single record
 *   POST students_api.php  {action: create|update|delete, ...}
 * Status codes: 200 OK, 201 Created, 400 Bad request, 401, 403, 404, 405, 409 Conflict, 422 Validation
 */
require_once __DIR__ . '/_bootstrap.php';

const COURSES = ['B.Tech Computer Engineering', 'B.Tech Information Technology', 'B.Tech Electronics & Communication'];

function validate_student(array $in, int $ignore): array
{
    $e = [];
    $d = ['enrollment_no' => strtoupper(trim($in['enrollment_no'] ?? '')), 'full_name' => trim($in['full_name'] ?? ''), 'email' => strtolower(trim($in['email'] ?? '')),
          'mobile' => trim($in['mobile'] ?? ''), 'course' => trim($in['course'] ?? ''), 'year' => (int)($in['year'] ?? 0), 'gender' => $in['gender'] ?? '', 'cgpa' => $in['cgpa'] ?? ''];
    if (!preg_match('/^\d{2}[A-Z]{3}\d{3}$/', $d['enrollment_no'])) { $e['enrollment_no'] = 'Format like 25DCE070'; }
    if (!preg_match('/^[A-Za-z][A-Za-z\s]{2,49}$/', $d['full_name'])) { $e['full_name'] = 'Name must be 3-50 letters'; }
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) { $e['email'] = 'Invalid email'; }
    if (!preg_match('/^[6-9]\d{9}$/', $d['mobile'])) { $e['mobile'] = 'Invalid 10-digit mobile'; }
    if (!in_array($d['course'], COURSES, true)) { $e['course'] = 'Invalid course'; }
    if ($d['year'] < 1 || $d['year'] > 4) { $e['year'] = 'Year must be 1-4'; }
    if (!in_array($d['gender'], ['Male', 'Female', 'Other'], true)) { $e['gender'] = 'Invalid gender'; }
    if (!is_numeric($d['cgpa']) || $d['cgpa'] < 0 || $d['cgpa'] > 10) { $e['cgpa'] = 'CGPA must be 0-10'; }
    return [$d, $e];
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    require_login([], true);
    if (isset($_GET['id'])) {
        $s = db()->prepare("SELECT * FROM students WHERE id = ? AND status = 'active'");
        $s->execute([(int)$_GET['id']]);
        $row = $s->fetch();
        $row ? json_out(['status' => 'success', 'data' => $row]) : json_out(['status' => 'error', 'message' => 'Student not found'], 404);
    }
    $q = trim($_GET['q'] ?? '');
    $page = max(1, (int)($_GET['page'] ?? 1));
    $per = min(50, max(1, (int)($_GET['per'] ?? 8)));
    $where = "status = 'active'"; $args = [];
    if ($q !== '') { $where .= ' AND (full_name LIKE ? OR email LIKE ? OR enrollment_no LIKE ?)'; $args = ["%$q%", "%$q%", "%$q%"]; }
    $c = db()->prepare("SELECT COUNT(*) FROM students WHERE $where"); $c->execute($args); $total = (int)$c->fetchColumn();
    $s = db()->prepare("SELECT * FROM students WHERE $where ORDER BY enrollment_no LIMIT ? OFFSET ?");
    foreach ($args as $i => $a) { $s->bindValue($i + 1, $a); }
    $s->bindValue(count($args) + 1, $per, PDO::PARAM_INT);
    $s->bindValue(count($args) + 2, ($page - 1) * $per, PDO::PARAM_INT);
    $s->execute();
    json_out(['status' => 'success', 'data' => $s->fetchAll(), 'meta' => ['total' => $total, 'page' => $page, 'per' => $per, 'pages' => max(1, (int)ceil($total / $per))]]);
}
if ($method !== 'POST') { header('Allow: GET, POST'); json_out(['status' => 'error', 'message' => 'Method not allowed'], 405); }

$user = api_guard_write();
$in = api_input();
$action = $in['action'] ?? '';
$id = (int)($in['id'] ?? 0);

if ($action === 'create' || $action === 'update') {
    [$d, $err] = validate_student($in, $id);
    if ($err) { json_out(['status' => 'error', 'message' => 'Validation failed', 'errors' => $err], 422); }
    $dup = db()->prepare('SELECT id FROM students WHERE (enrollment_no = ? OR email = ?) AND id <> ?');
    $dup->execute([$d['enrollment_no'], $d['email'], $id]);
    if ($dup->fetch()) { json_out(['status' => 'error', 'message' => 'Enrollment number or email already exists'], 409); }
    if ($action === 'create') {
        db()->prepare('INSERT INTO students (enrollment_no, full_name, email, mobile, course, year, gender, cgpa) VALUES (?,?,?,?,?,?,?,?)')->execute(array_values($d));
        $newId = (int)db()->lastInsertId();
        audit('API_STUDENT_CREATE', "Created student #$newId via API", $user['id']);
        json_out(['status' => 'success', 'message' => 'Student created', 'id' => $newId], 201);
    }
    $chk = db()->prepare("SELECT id FROM students WHERE id = ? AND status = 'active'"); $chk->execute([$id]);
    if (!$chk->fetch()) { json_out(['status' => 'error', 'message' => 'Student not found'], 404); }
    db()->prepare('UPDATE students SET enrollment_no=?, full_name=?, email=?, mobile=?, course=?, year=?, gender=?, cgpa=? WHERE id=?')->execute([...array_values($d), $id]);
    audit('API_STUDENT_UPDATE', "Updated student #$id via API", $user['id']);
    json_out(['status' => 'success', 'message' => 'Student updated']);
}
if ($action === 'delete') {
    $u = db()->prepare("UPDATE students SET status = 'deleted' WHERE id = ? AND status = 'active'"); $u->execute([$id]);
    if (!$u->rowCount()) { json_out(['status' => 'error', 'message' => 'Student not found'], 404); }
    audit('API_STUDENT_DELETE', "Soft-deleted student #$id via API", $user['id']);
    json_out(['status' => 'success', 'message' => 'Student deleted']);
}
json_out(['status' => 'error', 'message' => 'Unknown action'], 400);
