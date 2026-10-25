<?php
/** Practical 10: protected student dashboard (any logged-in user) */
require_once __DIR__ . '/layout.php';
$u = require_login();
$st = db()->prepare('SELECT last_login, created_at FROM users WHERE id = ?');
$st->execute([$u['id']]);
$info = $st->fetch();
$regs = db()->prepare('SELECT COUNT(*) FROM registrations r JOIN students s ON s.id = r.student_id JOIN users x ON x.email = s.email WHERE x.id = ?');
$regs->execute([$u['id']]);
page_header('My Dashboard', 'Practical 10', 'My Dashboard (Protected)');
$timeout = db_config()['session_timeout'];
?>
<div class="card" style="margin-bottom:1.5rem;">
  <span class="badge badge-success">Authenticated</span>
  <h1>Welcome, <?= e($u['name']) ?> 👋</h1>
  <p style="margin-bottom:0;">Role: <strong><?= e($u['role']) ?></strong> · Email: <strong><?= e($u['email']) ?></strong></p>
</div>
<section class="grid-4" style="margin-bottom:1.5rem;">
  <div class="stat-card"><div class="stat-icon green">🔐</div><div class="stat-info"><div class="stat-value">Active</div><div class="stat-label">Session status</div></div></div>
  <div class="stat-card"><div class="stat-icon blue">🕒</div><div class="stat-info"><div class="stat-value" style="font-size:1rem;"><?= e($info['last_login'] ?? '-') ?></div><div class="stat-label">Last login</div></div></div>
  <div class="stat-card"><div class="stat-icon amber">⏱️</div><div class="stat-info"><div class="stat-value"><?= (int)$timeout ?>s</div><div class="stat-label">Idle timeout</div></div></div>
  <div class="stat-card"><div class="stat-icon purple">🎟️</div><div class="stat-info"><div class="stat-value"><?= (int)$regs->fetchColumn() ?></div><div class="stat-label">My event registrations</div></div></div>
</section>
<div class="card"><h3>Try the access control</h3>
  <p>Students cannot open admin pages — the request below is blocked with HTTP 403.</p>
  <a class="btn btn-outline" href="admin_dashboard.php">Open Admin Dashboard</a>
  <a class="btn btn-danger" href="logout.php">Logout</a>
</div>
<?php page_footer();
