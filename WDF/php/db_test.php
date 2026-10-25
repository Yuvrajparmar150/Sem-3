<?php
/** Practical 8: connection test + schema overview (db.php PDO + prepared statements) */
require_once __DIR__ . '/layout.php';
$pdo = db();
$ver = $pdo->query(db_driver() === 'sqlite' ? 'SELECT sqlite_version()' : 'SELECT VERSION()')->fetchColumn();
$tables = ['users', 'students', 'events', 'registrations', 'audit_logs'];
$counts = [];
foreach ($tables as $t) { $counts[$t] = (int)$pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn(); }
// prepared statement demo
$st = $pdo->prepare('SELECT enrollment_no, full_name, course, cgpa FROM students WHERE enrollment_no = ?');
$st->execute(['25DCE070']);
$row = $st->fetch();
// JOIN demo
$joined = $pdo->query('SELECT s.full_name, e.title FROM registrations r JOIN students s ON s.id = r.student_id JOIN events e ON e.id = r.event_id ORDER BY r.id LIMIT 5')->fetchAll();

page_header('Database Connection Test', 'Practical 8', 'Database Connection Test (Practical 8)');
?>
<div class="card" style="margin-bottom:1.5rem;">
  <span class="badge badge-success">✔ Connected</span>
  <h1>StudentHub Database Connection</h1>
  <p>Driver: <strong><?= e(db_driver() === 'sqlite' ? 'PDO SQLite (sandbox demo)' : 'PDO MySQL') ?></strong> · Database: <strong><?= e(db_driver() === 'sqlite' ? basename(db_config()['sqlite']) : db_config()['dbname']) ?></strong> · Server version: <strong><?= e($ver) ?></strong></p>
</div>
<section class="grid-auto" style="margin-bottom:1.5rem;">
<?php foreach ($counts as $t => $n): ?>
  <div class="stat-card"><div class="stat-icon blue">🗄️</div><div class="stat-info"><div class="stat-value"><?= $n ?></div><div class="stat-label"><?= e($t) ?> rows</div></div></div>
<?php endforeach; ?>
</section>
<div class="grid-2">
  <div class="card"><h3>Prepared statement test</h3>
    <p><code>SELECT … FROM students WHERE enrollment_no = ?</code></p>
    <p><strong><?= e($row['full_name'] ?? 'n/a') ?></strong> (<?= e($row['enrollment_no'] ?? '') ?>) · <?= e($row['course'] ?? '') ?> · CGPA <?= e($row['cgpa'] ?? '') ?></p>
  </div>
  <div class="card"><h3>JOIN test (registrations → students → events)</h3>
    <div class="table-responsive"><table class="data-table"><thead><tr><th>Student</th><th>Event</th></tr></thead><tbody>
    <?php foreach ($joined as $j): ?><tr><td><?= e($j['full_name']) ?></td><td><?= e($j['title']) ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
  </div>
</div>
<?php page_footer();
