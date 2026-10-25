<?php
/**
 * Practical 12: Event Management CRUD with poster upload + validation (admin only)
 * Upload rules: jpg/png/webp, max 2 MB, MIME checked with finfo + getimagesize, file renamed randomly,
 * stored in /uploads/posters (script execution disabled by .htaccess), every change written to audit_logs.
 */
require_once __DIR__ . '/layout.php';
$user = require_login(['admin']);

const CATEGORIES = ['Hackathon', 'Workshop', 'Seminar', 'Competition', 'Cultural', 'Sports', 'Career', 'Business', 'Networking'];
const MAX_POSTER = 2 * 1024 * 1024;
$uploadDir = dirname(__DIR__) . '/uploads/posters/';

function handle_poster(array $f, array &$err): ?string
{
    global $uploadDir;
    if (($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { return null; }
    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE) { $err['poster'] = 'Poster is too large. Maximum allowed size is 2 MB.'; return null; }
    if ($f['error'] !== UPLOAD_ERR_OK) { $err['poster'] = 'Upload failed (error code ' . $f['error'] . ').'; return null; }
    if ($f['size'] > MAX_POSTER) { $err['poster'] = 'Poster is too large. Maximum allowed size is 2 MB.'; return null; }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if (!$ext || !@getimagesize($f['tmp_name'])) { $err['poster'] = 'Invalid file type. Only JPG, PNG or WEBP images are allowed.'; return null; }
    $name = 'poster_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
    if (!move_uploaded_file($f['tmp_name'], $uploadDir . $name)) {
        // CLI test fallback is not allowed: only real uploads are accepted
        $err['poster'] = 'Could not store the uploaded file.'; return null;
    }
    return $name;
}

$errors = [];
$form = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    // Body exceeded post_max_size: PHP discards everything, so give a meaningful message instead of a token error.
    flash('error', 'Upload rejected: the submitted file is larger than the server limit. Posters must be 2 MB or smaller.');
    redirect('events_crud.php?action=form');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid($_POST['csrf_token'] ?? null)) { flash('error', 'Invalid form token.'); redirect('events_crud.php'); }
    $act = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($act === 'save') {
        $d = ['title' => trim($_POST['title'] ?? ''), 'category' => $_POST['category'] ?? '', 'event_date' => $_POST['event_date'] ?? '',
              'venue' => trim($_POST['venue'] ?? ''), 'seats' => $_POST['seats'] ?? '', 'description' => trim($_POST['description'] ?? ''),
              'status' => ($_POST['status'] ?? 'open') === 'closed' ? 'closed' : 'open'];
        if (strlen($d['title']) < 5 || strlen($d['title']) > 150) { $errors['title'] = 'Title must be 5-150 characters.'; }
        if (!in_array($d['category'], CATEGORIES, true)) { $errors['category'] = 'Select a category.'; }
        $dt = DateTime::createFromFormat('Y-m-d', $d['event_date']);
        if (!$dt || $dt->format('Y-m-d') !== $d['event_date']) { $errors['event_date'] = 'Enter a valid date.'; }
        if (strlen($d['venue']) < 3) { $errors['venue'] = 'Venue is required.'; }
        if (!ctype_digit((string)$d['seats']) || (int)$d['seats'] < 1 || (int)$d['seats'] > 5000) { $errors['seats'] = 'Seats must be 1-5000.'; }
        $poster = handle_poster($_FILES['poster'] ?? [], $errors);
        if ($errors) {
            $form = $d + ['id' => $id, 'poster' => null];
        } elseif ($id) {
            $old = db()->prepare('SELECT poster FROM events WHERE id = ?'); $old->execute([$id]); $oldPoster = $old->fetchColumn();
            db()->prepare('UPDATE events SET title=?, category=?, event_date=?, venue=?, seats=?, description=?, status=?, poster=COALESCE(?, poster) WHERE id=?')
                ->execute([$d['title'], $d['category'], $d['event_date'], $d['venue'], (int)$d['seats'], $d['description'], $d['status'], $poster, $id]);
            if ($poster && $oldPoster && is_file($uploadDir . $oldPoster)) { unlink($uploadDir . $oldPoster); }
            audit('EVENT_UPDATE', "Updated event #$id: {$d['title']}");
            flash('success', 'Event updated successfully.');
            redirect('events_crud.php');
        } else {
            db()->prepare('INSERT INTO events (title, category, event_date, venue, seats, description, poster, status, created_by) VALUES (?,?,?,?,?,?,?,?,?)')
                ->execute([$d['title'], $d['category'], $d['event_date'], $d['venue'], (int)$d['seats'], $d['description'], $poster, $d['status'], $user['id']]);
            audit('EVENT_CREATE', 'Created event #' . db()->lastInsertId() . ': ' . $d['title']);
            flash('success', 'Event created successfully' . ($poster ? ' with poster.' : '.'));
            redirect('events_crud.php');
        }
    } elseif ($act === 'delete' && $id) {
        $old = db()->prepare('SELECT title, poster FROM events WHERE id = ?'); $old->execute([$id]); $ev = $old->fetch();
        if ($ev) {
            db()->prepare('DELETE FROM events WHERE id = ?')->execute([$id]);
            if ($ev['poster'] && is_file($uploadDir . $ev['poster'])) { unlink($uploadDir . $ev['poster']); }
            audit('EVENT_DELETE', "Deleted event #$id: {$ev['title']}");
            flash('success', 'Event deleted.');
        }
        redirect('events_crud.php');
    }
}

if (($_GET['action'] ?? '') === 'form' || $form) {
    if (!$form) {
        $id = (int)($_GET['id'] ?? 0);
        $form = ['id' => 0, 'title' => '', 'category' => CATEGORIES[0], 'event_date' => date('Y-m-d', strtotime('+14 days')), 'venue' => '', 'seats' => 100, 'description' => '', 'status' => 'open', 'poster' => null];
        if ($id) { $s = db()->prepare('SELECT * FROM events WHERE id = ?'); $s->execute([$id]); $form = $s->fetch() ?: $form; }
    }
    $fe = fn($k) => isset($errors[$k]) ? '<div class="invalid-feedback" style="display:block;">' . e($errors[$k]) . '</div>' : '';
    page_header($form['id'] ? 'Edit Event' : 'Add Event', 'Practical 12', 'Event Management (Practical 12)');
    ?>
    <div style="max-width:760px;margin:0 auto;"><div class="card" style="padding:2rem;">
      <h2><?= $form['id'] ? '✏️ Edit Event' : '➕ Add New Event' ?></h2>
      <form method="post" action="events_crud.php" enctype="multipart/form-data" novalidate>
        <?= csrf_field() ?><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)$form['id'] ?>">
        <div class="form-group"><label class="form-label" for="title">Title *</label><input class="form-control<?= isset($errors['title']) ? ' is-invalid' : '' ?>" id="title" name="title" value="<?= e($form['title']) ?>"><?= $fe('title') ?></div>
        <div class="grid-2">
          <div class="form-group"><label class="form-label" for="category">Category *</label><select class="form-control" id="category" name="category"><?php foreach (CATEGORIES as $c): ?><option <?= $form['category'] === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select><?= $fe('category') ?></div>
          <div class="form-group"><label class="form-label" for="event_date">Date *</label><input class="form-control<?= isset($errors['event_date']) ? ' is-invalid' : '' ?>" type="date" id="event_date" name="event_date" value="<?= e($form['event_date']) ?>"><?= $fe('event_date') ?></div>
          <div class="form-group"><label class="form-label" for="venue">Venue *</label><input class="form-control<?= isset($errors['venue']) ? ' is-invalid' : '' ?>" id="venue" name="venue" value="<?= e($form['venue']) ?>"><?= $fe('venue') ?></div>
          <div class="form-group"><label class="form-label" for="seats">Seats *</label><input class="form-control<?= isset($errors['seats']) ? ' is-invalid' : '' ?>" id="seats" name="seats" value="<?= e($form['seats']) ?>"><?= $fe('seats') ?></div>
          <div class="form-group"><label class="form-label" for="status">Status</label><select class="form-control" id="status" name="status"><option value="open" <?= $form['status'] === 'open' ? 'selected' : '' ?>>Open</option><option value="closed" <?= $form['status'] === 'closed' ? 'selected' : '' ?>>Closed</option></select></div>
          <div class="form-group"><label class="form-label" for="poster">Poster (JPG/PNG/WEBP, max 2 MB)</label><input class="form-control<?= isset($errors['poster']) ? ' is-invalid' : '' ?>" type="file" id="poster" name="poster" accept=".jpg,.jpeg,.png,.webp"><?= $fe('poster') ?></div>
        </div>
        <div class="form-group"><label class="form-label" for="description">Description</label><textarea class="form-control" id="description" name="description" rows="3"><?= e($form['description']) ?></textarea></div>
        <?php if (!empty($form['poster'])): ?><p>Current poster: <img src="../uploads/posters/<?= e($form['poster']) ?>" alt="Current poster" style="height:70px;border-radius:8px;vertical-align:middle;"></p><?php endif; ?>
        <div style="display:flex;gap:.75rem;"><button class="btn btn-primary" type="submit">💾 Save Event</button><a class="btn btn-outline" href="events_crud.php">Cancel</a></div>
      </form></div></div>
    <?php
    page_footer();
    exit;
}

$cat = $_GET['category'] ?? '';
$st = $_GET['status'] ?? '';
$q = trim($_GET['q'] ?? '');
$where = []; $args = [];
if (in_array($cat, CATEGORIES, true)) { $where[] = 'category = ?'; $args[] = $cat; }
if (in_array($st, ['open', 'closed'], true)) { $where[] = 'status = ?'; $args[] = $st; }
if ($q !== '') { $where[] = 'title LIKE ?'; $args[] = "%$q%"; }
$sql = 'SELECT * FROM events' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY event_date DESC';
$s = db()->prepare($sql); $s->execute($args); $events = $s->fetchAll();

page_header('Event Management', 'Practical 12', 'Event Management (Practical 12)');
?>
<div class="card" style="margin-bottom:1.5rem;"><span class="badge badge-primary">Practical 12 · Admin only</span><h1>Event Management</h1>
  <p style="margin-bottom:0;">Create, update and delete events with validated poster uploads. <?= count($events) ?> event(s) shown.</p></div>
<form method="get" action="events_crud.php" class="toolbar-container">
  <div class="search-input-wrapper"><span class="search-icon">🔍</span><input type="search" class="search-input" name="q" value="<?= e($q) ?>" placeholder="Search events..." aria-label="Search events"></div>
  <select name="category" class="filter-select" aria-label="Category"><option value="">All categories</option><?php foreach (CATEGORIES as $c): ?><option <?= $cat === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select>
  <select name="status" class="filter-select" aria-label="Status"><option value="">Open &amp; closed</option><option value="open" <?= $st === 'open' ? 'selected' : '' ?>>Open</option><option value="closed" <?= $st === 'closed' ? 'selected' : '' ?>>Closed</option></select>
  <button class="btn btn-primary btn-sm" type="submit">Filter</button><a class="btn btn-outline btn-sm" href="events_crud.php">Reset</a>
  <a class="btn btn-accent btn-sm" href="events_crud.php?action=form">➕ Add Event</a>
</form>
<div class="card" style="padding:0;margin-top:1rem;"><div class="table-responsive"><table class="data-table">
  <thead><tr><th>Poster</th><th>Title</th><th>Category</th><th>Date</th><th>Venue</th><th>Seats</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach ($events as $ev): ?>
    <tr>
      <td><?php if ($ev['poster']): ?><img src="../uploads/posters/<?= e($ev['poster']) ?>" alt="Poster of <?= e($ev['title']) ?>" style="width:56px;height:40px;object-fit:cover;border-radius:6px;"><?php else: ?><span style="color:var(--text-muted);">—</span><?php endif; ?></td>
      <td><strong><?= e($ev['title']) ?></strong></td><td><span class="badge badge-primary"><?= e($ev['category']) ?></span></td><td><?= e($ev['event_date']) ?></td><td><?= e($ev['venue']) ?></td><td><?= (int)$ev['seats'] ?></td>
      <td><span class="badge <?= $ev['status'] === 'open' ? 'badge-success' : 'badge-warning' ?>"><?= e(ucfirst($ev['status'])) ?></span></td>
      <td style="white-space:nowrap;"><a class="btn btn-outline btn-sm" href="events_crud.php?action=form&id=<?= (int)$ev['id'] ?>">Edit</a>
        <form method="post" action="events_crud.php" style="display:inline;" onsubmit="return confirm('Delete this event permanently?');"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$ev['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form></td>
    </tr>
  <?php endforeach; if (!$events): ?><tr><td colspan="8" style="text-align:center;padding:2rem;">No events found.</td></tr><?php endif; ?>
  </tbody></table></div></div>
<?php page_footer();
