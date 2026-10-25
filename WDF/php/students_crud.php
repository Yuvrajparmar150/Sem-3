<?php
/**
 * Practical 11: Student Management CRUD (admin only) - search, filter, pagination, CSV export, soft delete
 * All queries use prepared statements.
 */
require_once __DIR__ . '/layout.php';
$user = require_login(['admin']);

const COURSES = ['B.Tech Computer Engineering', 'B.Tech Information Technology', 'B.Tech Electronics & Communication'];

/* ---------- Validation ---------- */
function validate_student(array $in, ?int $ignoreId): array
{
    $err = [];
    $d = [
        'enrollment_no' => strtoupper(trim($in['enrollment_no'] ?? '')),
        'full_name' => trim($in['full_name'] ?? ''),
        'email' => strtolower(trim($in['email'] ?? '')),
        'mobile' => trim($in['mobile'] ?? ''),
        'course' => trim($in['course'] ?? ''),
        'year' => (int)($in['year'] ?? 0),
        'gender' => $in['gender'] ?? '',
        'cgpa' => $in['cgpa'] ?? '',
    ];
    if (!preg_match('/^\d{2}[A-Z]{3}\d{3}$/', $d['enrollment_no'])) { $err['enrollment_no'] = 'Format like 25DCE070.'; }
    if (!preg_match('/^[A-Za-z][A-Za-z\s]{2,49}$/', $d['full_name'])) { $err['full_name'] = 'Name must be 3-50 letters.'; }
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) { $err['email'] = 'Enter a valid email.'; }
    if (!preg_match('/^[6-9]\d{9}$/', $d['mobile'])) { $err['mobile'] = '10-digit mobile starting with 6-9.'; }
    if (!in_array($d['course'], COURSES, true)) { $err['course'] = 'Select a course.'; }
    if ($d['year'] < 1 || $d['year'] > 4) { $err['year'] = 'Year must be 1-4.'; }
    if (!in_array($d['gender'], ['Male', 'Female', 'Other'], true)) { $err['gender'] = 'Select gender.'; }
    if (!is_numeric($d['cgpa']) || $d['cgpa'] < 0 || $d['cgpa'] > 10) { $err['cgpa'] = 'CGPA must be between 0 and 10.'; }
    if (!$err) {
        $q = db()->prepare('SELECT id FROM students WHERE (enrollment_no = ? OR email = ?) AND id <> ?');
        $q->execute([$d['enrollment_no'], $d['email'], $ignoreId ?? 0]);
        if ($q->fetch()) { $err['email'] = 'Enrollment number or email already exists.'; }
    }
    return [$d, $err];
}

/* ---------- CSV export (intermediate) ---------- */
if (($_GET['action'] ?? '') === 'export') {
    $rows = db()->query("SELECT enrollment_no, full_name, email, mobile, course, year, gender, cgpa FROM students WHERE status = 'active' ORDER BY enrollment_no")->fetchAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="students_' . date('Ymd') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Enrollment No', 'Name', 'Email', 'Mobile', 'Course', 'Year', 'Gender', 'CGPA']);
    foreach ($rows as $r) { fputcsv($out, $r); }
    audit('STUDENT_EXPORT', 'Exported ' . count($rows) . ' students to CSV');
    exit;
}

/* ---------- POST actions: save / delete / restore ---------- */
$errors = [];
$form = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid($_POST['csrf_token'] ?? null)) { flash('error', 'Invalid form token.'); redirect('students_crud.php'); }
    $act = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($act === 'save') {
        [$d, $errors] = validate_student($_POST, $id ?: null);
        if ($errors) {
            $form = $d + ['id' => $id];
        } elseif ($id) {
            db()->prepare('UPDATE students SET enrollment_no=?, full_name=?, email=?, mobile=?, course=?, year=?, gender=?, cgpa=? WHERE id=?')
                ->execute([$d['enrollment_no'], $d['full_name'], $d['email'], $d['mobile'], $d['course'], $d['year'], $d['gender'], $d['cgpa'], $id]);
            audit('STUDENT_UPDATE', "Updated student #$id ({$d['enrollment_no']})");
            flash('success', "Student {$d['full_name']} updated successfully.");
            redirect('students_crud.php');
        } else {
            db()->prepare('INSERT INTO students (enrollment_no, full_name, email, mobile, course, year, gender, cgpa) VALUES (?,?,?,?,?,?,?,?)')
                ->execute([$d['enrollment_no'], $d['full_name'], $d['email'], $d['mobile'], $d['course'], $d['year'], $d['gender'], $d['cgpa']]);
            audit('STUDENT_CREATE', 'Added student ' . $d['enrollment_no']);
            flash('success', "Student {$d['full_name']} added successfully.");
            redirect('students_crud.php');
        }
    } elseif ($act === 'delete' && $id) {
        db()->prepare("UPDATE students SET status = 'deleted' WHERE id = ?")->execute([$id]);
        audit('STUDENT_DELETE', "Soft-deleted student #$id");
        flash('success', 'Student moved to deleted records (soft delete).');
        redirect('students_crud.php');
    } elseif ($act === 'restore' && $id) {
        db()->prepare("UPDATE students SET status = 'active' WHERE id = ?")->execute([$id]);
        audit('STUDENT_RESTORE', "Restored student #$id");
        flash('success', 'Student restored.');
        redirect('students_crud.php?status=deleted');
    }
}

/* ---------- Add / Edit form ---------- */
if (($_GET['action'] ?? '') === 'form' || $form) {
    if (!$form) {
        $id = (int)($_GET['id'] ?? 0);
        $form = ['id' => 0, 'enrollment_no' => '', 'full_name' => '', 'email' => '', 'mobile' => '', 'course' => COURSES[0], 'year' => 2, 'gender' => 'Male', 'cgpa' => ''];
        if ($id) {
            $q = db()->prepare('SELECT * FROM students WHERE id = ?');
            $q->execute([$id]);
            $form = $q->fetch() ?: $form;
        }
    }
    page_header($form['id'] ? 'Edit Student' : 'Add Student', 'Practical 11', 'Student Management (Practical 11)');
    $fe = function ($k) use ($errors) { return isset($errors[$k]) ? '<div class="invalid-feedback" style="display:block;">' . e($errors[$k]) . '</div>' : ''; };
    ?>
    <div style="max-width:720px;margin:0 auto;"><div class="card" style="padding:2rem;">
      <h2><?= $form['id'] ? '✏️ Edit Student' : '➕ Add New Student' ?></h2>
      <form method="post" action="students_crud.php" novalidate>
        <?= csrf_field() ?><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)$form['id'] ?>">
        <div class="grid-2">
          <div class="form-group"><label class="form-label" for="enrollment_no">Enrollment No *</label><input class="form-control<?= isset($errors['enrollment_no']) ? ' is-invalid' : '' ?>" id="enrollment_no" name="enrollment_no" value="<?= e($form['enrollment_no']) ?>" placeholder="25DCE070"><?= $fe('enrollment_no') ?></div>
          <div class="form-group"><label class="form-label" for="full_name">Full name *</label><input class="form-control<?= isset($errors['full_name']) ? ' is-invalid' : '' ?>" id="full_name" name="full_name" value="<?= e($form['full_name']) ?>"><?= $fe('full_name') ?></div>
          <div class="form-group"><label class="form-label" for="email">Email *</label><input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" id="email" name="email" value="<?= e($form['email']) ?>"><?= $fe('email') ?></div>
          <div class="form-group"><label class="form-label" for="mobile">Mobile *</label><input class="form-control<?= isset($errors['mobile']) ? ' is-invalid' : '' ?>" id="mobile" name="mobile" value="<?= e($form['mobile']) ?>"><?= $fe('mobile') ?></div>
          <div class="form-group"><label class="form-label" for="course">Course *</label><select class="form-control" id="course" name="course"><?php foreach (COURSES as $c): ?><option <?= $form['course'] === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select><?= $fe('course') ?></div>
          <div class="form-group"><label class="form-label" for="year">Year *</label><select class="form-control" id="year" name="year"><?php for ($y = 1; $y <= 4; $y++): ?><option value="<?= $y ?>" <?= (int)$form['year'] === $y ? 'selected' : '' ?>>Year <?= $y ?></option><?php endfor; ?></select><?= $fe('year') ?></div>
          <div class="form-group"><label class="form-label" for="gender">Gender *</label><select class="form-control" id="gender" name="gender"><?php foreach (['Male', 'Female', 'Other'] as $g): ?><option <?= $form['gender'] === $g ? 'selected' : '' ?>><?= $g ?></option><?php endforeach; ?></select><?= $fe('gender') ?></div>
          <div class="form-group"><label class="form-label" for="cgpa">CGPA *</label><input class="form-control<?= isset($errors['cgpa']) ? ' is-invalid' : '' ?>" id="cgpa" name="cgpa" value="<?= e($form['cgpa']) ?>" placeholder="8.50"><?= $fe('cgpa') ?></div>
        </div>
        <div style="display:flex;gap:.75rem;margin-top:1rem;"><button class="btn btn-primary" type="submit">💾 Save Student</button><a class="btn btn-outline" href="students_crud.php">Cancel</a></div>
      </form></div></div>
    <?php
    page_footer();
    exit;
}

/* ---------- List with search / filter / pagination ---------- */
$q = trim($_GET['q'] ?? '');
$course = $_GET['course'] ?? '';
$status = ($_GET['status'] ?? 'active') === 'deleted' ? 'deleted' : 'active';
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 8;
$where = ['status = ?'];
$args = [$status];
if ($q !== '') { $where[] = '(full_name LIKE ? OR email LIKE ? OR enrollment_no LIKE ?)'; array_push($args, "%$q%", "%$q%", "%$q%"); }
if ($course !== '' && in_array($course, COURSES, true)) { $where[] = 'course = ?'; $args[] = $course; }
$w = implode(' AND ', $where);
$cnt = db()->prepare("SELECT COUNT(*) FROM students WHERE $w");
$cnt->execute($args);
$total = (int)$cnt->fetchColumn();
$pages = max(1, (int)ceil($total / $per));
$page = min($page, $pages);
$st = db()->prepare("SELECT * FROM students WHERE $w ORDER BY enrollment_no LIMIT ? OFFSET ?");
foreach ($args as $i => $a) { $st->bindValue($i + 1, $a); }
$st->bindValue(count($args) + 1, $per, PDO::PARAM_INT);
$st->bindValue(count($args) + 2, ($page - 1) * $per, PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll();
$qs = fn(array $o) => '?' . http_build_query(array_merge(['q' => $q, 'course' => $course, 'status' => $status], $o));

page_header('Student Management', 'Practical 11', 'Student Management (Practical 11)');
?>
<div class="card" style="margin-bottom:1.5rem;">
  <span class="badge badge-primary">Practical 11 · Admin only</span>
  <h1>Student Management</h1>
  <p style="margin-bottom:0;">Add, view, edit, soft-delete, search and filter student records. <?= $total ?> record(s) found.</p>
</div>
<form method="get" action="students_crud.php" class="toolbar-container">
  <div class="search-input-wrapper"><span class="search-icon">🔍</span><input type="search" name="q" class="search-input" value="<?= e($q) ?>" placeholder="Search by name, email or enrollment no..." aria-label="Search students"></div>
  <select name="course" class="filter-select" aria-label="Filter by course"><option value="">All courses</option><?php foreach (COURSES as $c): ?><option <?= $course === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select>
  <select name="status" class="filter-select" aria-label="Status"><option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option><option value="deleted" <?= $status === 'deleted' ? 'selected' : '' ?>>Deleted</option></select>
  <button class="btn btn-primary btn-sm" type="submit">Apply</button>
  <a class="btn btn-outline btn-sm" href="students_crud.php">Reset</a>
  <a class="btn btn-accent btn-sm" href="students_crud.php?action=form">➕ Add Student</a>
  <a class="btn btn-outline btn-sm" href="students_crud.php?action=export">⬇ Export CSV</a>
</form>
<div class="card" style="padding:0;margin-top:1rem;"><div class="table-responsive"><table class="data-table">
  <thead><tr><th>#</th><th>Enrollment</th><th>Name</th><th>Email</th><th>Course</th><th>Yr</th><th>CGPA</th><th>Actions</th></tr></thead><tbody>
  <?php if (!$rows): ?><tr><td colspan="8" style="text-align:center;padding:2rem;">No students found.</td></tr><?php endif; ?>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td><td><strong><?= e($r['enrollment_no']) ?></strong></td><td><?= e($r['full_name']) ?></td><td><?= e($r['email']) ?></td>
      <td><?= e($r['course']) ?></td><td><?= (int)$r['year'] ?></td><td><span class="badge badge-success"><?= e(number_format((float)$r['cgpa'], 2)) ?></span></td>
      <td style="white-space:nowrap;">
      <?php if ($status === 'active'): ?>
        <a class="btn btn-outline btn-sm" href="students_crud.php?action=form&id=<?= (int)$r['id'] ?>">Edit</a>
        <form method="post" action="students_crud.php" style="display:inline;" onsubmit="return confirm('Delete <?= e(addslashes($r['full_name'])) ?>? This is a soft delete and can be restored.');">
          <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
      <?php else: ?>
        <form method="post" action="students_crud.php" style="display:inline;"><?= csrf_field() ?><input type="hidden" name="action" value="restore"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn btn-primary btn-sm" type="submit">Restore</button></form>
      <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div></div>
<div class="pagination-container">
  <div class="pagination-info">Page <strong><?= $page ?></strong> of <strong><?= $pages ?></strong> · <?= $total ?> records</div>
  <div class="pagination-pages">
    <?php if ($page > 1): ?><a class="page-btn" href="<?= e($qs(['page' => $page - 1])) ?>">‹</a><?php endif; ?>
    <?php for ($i = 1; $i <= $pages; $i++): ?><a class="page-btn<?= $i === $page ? ' active' : '' ?>" href="<?= e($qs(['page' => $i])) ?>"><?= $i ?></a><?php endfor; ?>
    <?php if ($page < $pages): ?><a class="page-btn" href="<?= e($qs(['page' => $page + 1])) ?>">›</a><?php endif; ?>
  </div>
</div>
<?php page_footer();
