<?php
/**
 * Practical 14: Admin dashboard - RBAC, dynamic menu, SQL aggregation, audit log viewer,
 * active/inactive user toggle, registrations-by-month chart
 */
require_once __DIR__ . '/layout.php';
$admin = require_login(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_user') {
    if (!csrf_valid($_POST['csrf_token'] ?? null)) { flash('error', 'Invalid form token.'); redirect('admin_dashboard.php'); }
    $id = (int)($_POST['id'] ?? 0);
    if ($id === (int)$admin['id']) { flash('warning', 'You cannot deactivate your own account.'); redirect('admin_dashboard.php'); }
    $s = db()->prepare('SELECT email, status FROM users WHERE id = ?'); $s->execute([$id]); $t = $s->fetch();
    if ($t) {
        $new = $t['status'] === 'active' ? 'inactive' : 'active';
        db()->prepare('UPDATE users SET status = ? WHERE id = ?')->execute([$new, $id]);
        audit('USER_STATUS', "Set {$t['email']} to $new");
        flash('success', "{$t['email']} is now $new.");
    }
    redirect('admin_dashboard.php');
}

$one = fn(string $sql) => (int)db()->query($sql)->fetchColumn();
$stats = [
    ['👥', 'blue', $one('SELECT COUNT(*) FROM users'), 'Total users'],
    ['🎓', 'green', $one("SELECT COUNT(*) FROM students WHERE status = 'active'"), 'Active students'],
    ['🎟️', 'amber', $one('SELECT COUNT(*) FROM events'), 'Events (' . $one("SELECT COUNT(*) FROM events WHERE status='open'") . ' open)'],
    ['📝', 'purple', $one('SELECT COUNT(*) FROM registrations'), 'Event registrations'],
];
$latest = db()->query('SELECT s.full_name, s.enrollment_no, e.title, r.registered_at FROM registrations r JOIN students s ON s.id = r.student_id JOIN events e ON e.id = r.event_id ORDER BY r.registered_at DESC, r.id DESC LIMIT 6')->fetchAll();
$byMonth = db()->query('SELECT ' . sql_month('registered_at') . ' AS m, COUNT(*) AS c FROM registrations GROUP BY m ORDER BY m')->fetchAll();
$maxC = max(array_column($byMonth, 'c') ?: [1]);
$users = db()->query('SELECT id, full_name, email, role, status, last_login FROM users ORDER BY id')->fetchAll();
$logs = db()->query('SELECT a.id, a.action, a.details, a.ip_address, a.created_at, u.email FROM audit_logs a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.id DESC LIMIT 10')->fetchAll();

page_header('Admin Dashboard', 'Practical 14', 'Admin Dashboard (Practical 14)');
?>
<div class="card" style="margin-bottom:1.5rem;"><span class="badge badge-primary">Practical 14 · RBAC protected</span>
  <h1>Admin Dashboard</h1><p style="margin-bottom:0;">Live statistics from MySQL, role-based menu, user status control and audit trail.</p></div>
<section class="grid-4" style="margin-bottom:1.5rem;">
<?php foreach ($stats as [$ic, $col, $val, $lab]): ?>
  <div class="stat-card"><div class="stat-icon <?= $col ?>"><?= $ic ?></div><div class="stat-info"><div class="stat-value"><?= $val ?></div><div class="stat-label"><?= e($lab) ?></div></div></div>
<?php endforeach; ?>
</section>
<div class="grid-2" style="margin-bottom:1.5rem;">
  <div class="card"><h3>📈 Event registrations by month</h3>
    <?php foreach ($byMonth as $m): $pct = round($m['c'] / $maxC * 100); ?>
      <div style="display:flex;align-items:center;gap:.75rem;margin:.5rem 0;"><span style="width:70px;font-size:.85rem;color:var(--text-secondary);"><?= e($m['m']) ?></span>
        <div style="flex:1;background:var(--bg-muted);border-radius:var(--radius-full);height:16px;overflow:hidden;"><div style="width:<?= $pct ?>%;height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));"></div></div>
        <strong style="width:28px;text-align:right;"><?= (int)$m['c'] ?></strong></div>
    <?php endforeach; ?>
  </div>
  <div class="card"><h3>🆕 Latest event registrations</h3>
    <div class="table-responsive"><table class="data-table"><thead><tr><th>Student</th><th>Event</th><th>When</th></tr></thead><tbody>
    <?php foreach ($latest as $l): ?><tr><td><?= e($l['full_name']) ?><br><small><?= e($l['enrollment_no']) ?></small></td><td><?= e($l['title']) ?></td><td><?= e(substr($l['registered_at'], 0, 10)) ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
  </div>
</div>
<div class="card" style="margin-bottom:1.5rem;padding:0;"><div style="padding:1.25rem 1.5rem 0;"><h3>👤 Users &amp; access status</h3></div><div class="table-responsive"><table class="data-table">
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last login</th><th>Action</th></tr></thead><tbody>
  <?php foreach ($users as $u): ?>
    <tr><td><?= (int)$u['id'] ?></td><td><?= e($u['full_name']) ?></td><td><?= e($u['email']) ?></td><td><span class="badge badge-primary"><?= e($u['role']) ?></span></td>
      <td><span class="badge <?= $u['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= e($u['status']) ?></span></td><td><?= e($u['last_login'] ?? '—') ?></td>
      <td><form method="post" action="admin_dashboard.php" style="display:inline;"><?= csrf_field() ?><input type="hidden" name="action" value="toggle_user"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
        <button class="btn btn-outline btn-sm" type="submit"><?= $u['status'] === 'active' ? 'Deactivate' : 'Activate' ?></button></form></td></tr>
  <?php endforeach; ?></tbody></table></div></div>
<div class="card" style="padding:0;"><div style="padding:1.25rem 1.5rem 0;"><h3>🧾 Audit log (latest 10)</h3></div><div class="table-responsive"><table class="data-table">
  <thead><tr><th>#</th><th>Time</th><th>User</th><th>Action</th><th>Details</th><th>IP</th></tr></thead><tbody>
  <?php foreach ($logs as $g): ?><tr><td><?= (int)$g['id'] ?></td><td><?= e($g['created_at']) ?></td><td><?= e($g['email'] ?? 'guest') ?></td><td><span class="badge badge-warning"><?= e($g['action']) ?></span></td><td><?= e($g['details']) ?></td><td><?= e($g['ip_address']) ?></td></tr><?php endforeach; ?>
  </tbody></table></div></div>
<?php page_footer();
