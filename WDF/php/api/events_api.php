<?php
/** Practical 13: Events JSON API (GET list/single, POST create|update|delete) */
require_once __DIR__ . '/_bootstrap.php';

const CATEGORIES = ['Hackathon', 'Workshop', 'Seminar', 'Competition', 'Cultural', 'Sports', 'Career', 'Business', 'Networking'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    require_login([], true);
    if (isset($_GET['id'])) {
        $s = db()->prepare('SELECT * FROM events WHERE id = ?'); $s->execute([(int)$_GET['id']]);
        $r = $s->fetch();
        $r ? json_out(['status' => 'success', 'data' => $r]) : json_out(['status' => 'error', 'message' => 'Event not found'], 404);
    }
    $q = trim($_GET['q'] ?? ''); $page = max(1, (int)($_GET['page'] ?? 1)); $per = min(50, max(1, (int)($_GET['per'] ?? 8)));
    $where = '1=1'; $args = [];
    if ($q !== '') { $where .= ' AND (title LIKE ? OR venue LIKE ?)'; $args = ["%$q%", "%$q%"]; }
    $c = db()->prepare("SELECT COUNT(*) FROM events WHERE $where"); $c->execute($args); $total = (int)$c->fetchColumn();
    $s = db()->prepare("SELECT id, title, category, event_date, venue, seats, status FROM events WHERE $where ORDER BY event_date DESC LIMIT ? OFFSET ?");
    foreach ($args as $i => $a) { $s->bindValue($i + 1, $a); }
    $s->bindValue(count($args) + 1, $per, PDO::PARAM_INT);
    $s->bindValue(count($args) + 2, ($page - 1) * $per, PDO::PARAM_INT);
    $s->execute();
    json_out(['status' => 'success', 'data' => $s->fetchAll(), 'meta' => ['total' => $total, 'page' => $page, 'per' => $per, 'pages' => max(1, (int)ceil($total / $per))]]);
}
if ($method !== 'POST') { header('Allow: GET, POST'); json_out(['status' => 'error', 'message' => 'Method not allowed'], 405); }

$user = api_guard_write();
$in = api_input();
$action = $in['action'] ?? ''; $id = (int)($in['id'] ?? 0);

if ($action === 'create' || $action === 'update') {
    $d = ['title' => trim($in['title'] ?? ''), 'category' => $in['category'] ?? '', 'event_date' => $in['event_date'] ?? '', 'venue' => trim($in['venue'] ?? ''),
          'seats' => $in['seats'] ?? '', 'status' => ($in['status'] ?? 'open') === 'closed' ? 'closed' : 'open'];
    $err = [];
    if (strlen($d['title']) < 5 || strlen($d['title']) > 150) { $err['title'] = 'Title must be 5-150 characters'; }
    if (!in_array($d['category'], CATEGORIES, true)) { $err['category'] = 'Invalid category'; }
    $dt = DateTime::createFromFormat('Y-m-d', (string)$d['event_date']);
    if (!$dt || $dt->format('Y-m-d') !== $d['event_date']) { $err['event_date'] = 'Invalid date'; }
    if (strlen($d['venue']) < 3) { $err['venue'] = 'Venue required'; }
    if (!ctype_digit((string)$d['seats']) || (int)$d['seats'] < 1) { $err['seats'] = 'Seats must be a positive number'; }
    if ($err) { json_out(['status' => 'error', 'message' => 'Validation failed', 'errors' => $err], 422); }
    if ($action === 'create') {
        db()->prepare('INSERT INTO events (title, category, event_date, venue, seats, status, created_by) VALUES (?,?,?,?,?,?,?)')
            ->execute([$d['title'], $d['category'], $d['event_date'], $d['venue'], (int)$d['seats'], $d['status'], $user['id']]);
        $nid = (int)db()->lastInsertId();
        audit('API_EVENT_CREATE', "Created event #$nid via API", $user['id']);
        json_out(['status' => 'success', 'message' => 'Event created', 'id' => $nid], 201);
    }
    $u = db()->prepare('UPDATE events SET title=?, category=?, event_date=?, venue=?, seats=?, status=? WHERE id=?');
    $u->execute([$d['title'], $d['category'], $d['event_date'], $d['venue'], (int)$d['seats'], $d['status'], $id]);
    $ex = db()->prepare('SELECT id FROM events WHERE id = ?'); $ex->execute([$id]);
    if (!$ex->fetch()) { json_out(['status' => 'error', 'message' => 'Event not found'], 404); }
    audit('API_EVENT_UPDATE', "Updated event #$id via API", $user['id']);
    json_out(['status' => 'success', 'message' => 'Event updated']);
}
if ($action === 'delete') {
    $s = db()->prepare('DELETE FROM events WHERE id = ?'); $s->execute([$id]);
    if (!$s->rowCount()) { json_out(['status' => 'error', 'message' => 'Event not found'], 404); }
    audit('API_EVENT_DELETE', "Deleted event #$id via API", $user['id']);
    json_out(['status' => 'success', 'message' => 'Event deleted']);
}
json_out(['status' => 'error', 'message' => 'Unknown action'], 400);
