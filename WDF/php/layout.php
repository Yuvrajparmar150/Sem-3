<?php
/**
 * Shared helpers + page layout for Practicals 8-15
 * File: php/layout.php  (re-uses ../css/style.css and ../js/main.js unchanged)
 */
require_once __DIR__ . '/db.php';

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

/* ---------- Sessions, CSRF, flash ---------- */
function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
}
function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function csrf_valid(?string $t): bool { start_session(); return is_string($t) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t); }
function flash(string $type, string $msg): void { start_session(); $_SESSION['flash'][] = [$type, $msg]; }
function redirect(string $to): void { header('Location: ' . $to); exit; }

/* ---------- Audit log (Practical 9 / 12 / 14) ---------- */
function audit(string $action, string $details = '', ?int $userId = null): void
{
    start_session();
    $uid = $userId ?? ($_SESSION['user']['id'] ?? null);
    $st = db()->prepare('INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)');
    $st->execute([$uid, $action, mb_substr($details, 0, 250), $_SERVER['REMOTE_ADDR'] ?? 'cli']);
}

/* ---------- Authentication (Practical 10) ---------- */
function login_user(array $u, bool $remember): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = ['id' => (int)$u['id'], 'name' => $u['full_name'], 'email' => $u['email'], 'role' => $u['role']];
    $_SESSION['last_activity'] = time();
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    db()->prepare('UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?')->execute([$u['id']]);
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $exp = date('Y-m-d H:i:s', time() + 30 * 86400);
        db()->prepare('UPDATE users SET remember_token_hash = ?, remember_expires = ? WHERE id = ?')
            ->execute([hash('sha256', $token), $exp, $u['id']]);
        setcookie('sh_remember', $u['id'] . ':' . $token, ['expires' => time() + 30 * 86400, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
    }
}
function try_remember_login(): void
{
    if (!empty($_SESSION['user']) || empty($_COOKIE['sh_remember'])) { return; }
    [$id, $token] = array_pad(explode(':', $_COOKIE['sh_remember'], 2), 2, '');
    if (!ctype_digit($id) || $token === '') { return; }
    $st = db()->prepare("SELECT * FROM users WHERE id = ? AND status = 'active'");
    $st->execute([$id]);
    $u = $st->fetch();
    if ($u && $u['remember_token_hash'] && $u['remember_expires'] > date('Y-m-d H:i:s')
        && hash_equals($u['remember_token_hash'], hash('sha256', $token))) {
        login_user($u, true);              // rotates the token
        audit('LOGIN_REMEMBER', 'Auto-login via remember-me token', (int)$u['id']);
    }
}
function current_user(): ?array
{
    start_session();
    $timeout = db_config()['session_timeout'];
    if (!empty($_SESSION['user']) && isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $timeout) {
        $_SESSION = [];
        session_destroy();
        start_session();
        $_SESSION['timed_out'] = true;
        return null;
    }
    if (empty($_SESSION['user'])) { try_remember_login(); }
    if (!empty($_SESSION['user'])) { $_SESSION['last_activity'] = time(); return $_SESSION['user']; }
    return null;
}
/** Block unauthenticated / unauthorised access. $roles = [] means any logged-in user. */
function require_login(array $roles = [], bool $json = false): array
{
    $u = current_user();
    if (!$u) {
        if ($json) { json_out(['status' => 'error', 'message' => 'Authentication required'], 401); }
        redirect('login.php' . (!empty($_SESSION['timed_out']) ? '?timeout=1' : '?denied=1'));
    }
    if ($roles && !in_array($u['role'], $roles, true)) {
        if ($json) { json_out(['status' => 'error', 'message' => 'Forbidden: admin only'], 403); }
        http_response_code(403);
        page_header('Access Denied');
        echo '<div class="card"><span class="badge badge-danger">403 Forbidden</span><h2>Unauthorized access blocked</h2><p>Your role (<strong>' . e($u['role']) . '</strong>) is not permitted to open this page.</p><a class="btn btn-primary" href="my_dashboard.php">Back to my dashboard</a></div>';
        page_footer();
        exit;
    }
    return $u;
}
function json_out(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/* ---------- Layout (same markup/classes as the static pages) ---------- */
function menu_for(?array $u): array
{
    if (!$u) {
        return [['login.php', 'Login'], ['register_db.php', 'Register'], ['../events.html', 'Events']];
    }
    if ($u['role'] === 'admin') {
        return [['admin_dashboard.php', 'Admin Dashboard'], ['students_crud.php', 'Students'], ['events_crud.php', 'Events'], ['ajax_crud.php', 'AJAX CRUD'], ['logout.php', 'Logout']];
    }
    return [['my_dashboard.php', 'My Dashboard'], ['../events.html', 'Events'], ['../profile.html', 'Profile'], ['logout.php', 'Logout']];
}
function page_header(string $title, string $badge = 'StudentHub', string $crumb = ''): void
{
    $u = current_user();
    $menu = menu_for($u);
    $self = basename($_SERVER['SCRIPT_NAME']);
    ?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title) ?> | StudentHub</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>/* page-level only: keep header inside the viewport on phones (style.css is unchanged) */
    @media (max-width: 600px) { .nav-actions .badge { display: none; } .data-table { min-width: 640px; } }
    .grid-2 > *, .grid-4 > * { min-width: 0; }
  </style>
</head>
<body>
  <a href="#main-content" class="skip-link">Skip to main content</a>
  <header class="site-header">
    <div class="container nav-container">
      <a href="../index.html" class="brand-logo">
        <span class="logo-icon">🎓</span><span>StudentHub</span>
        <span class="badge-tag"><?= e($badge) ?></span>
      </a>
      <nav aria-label="Main Navigation">
        <ul class="nav-links">
          <li class="nav-item"><a href="../index.html" class="nav-link">Home</a></li>
          <?php foreach ($menu as [$href, $label]): ?>
          <li class="nav-item"><a href="<?= e($href) ?>" class="nav-link<?= $href === $self ? ' active' : '' ?>"><?= e($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>
      <div class="nav-actions">
        <button class="theme-toggle-btn" aria-label="Toggle theme">🌙</button>
        <?php if ($u): ?><span class="badge badge-primary"><?= e($u['name']) ?> · <?= e($u['role']) ?></span><?php endif; ?>
        <button class="hamburger-btn" aria-label="Open menu">☰</button>
      </div>
    </div>
  </header>
  <div class="mobile-nav-overlay"></div>
  <aside class="mobile-nav-drawer" aria-label="Mobile Navigation">
    <div class="mobile-nav-header">
      <div class="brand-logo"><span class="logo-icon">🎓</span><span>StudentHub</span></div>
      <button class="mobile-nav-close btn btn-outline btn-sm">✕</button>
    </div>
    <ul class="mobile-nav-links">
      <li><a href="../index.html" class="nav-link">🏠 Home</a></li>
      <?php foreach ($menu as [$href, $label]): ?>
      <li><a href="<?= e($href) ?>" class="nav-link"><?= e($label) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </aside>
  <main id="main-content" class="page-wrapper container">
    <div class="breadcrumb">
      <a href="../index.html">Home</a><span class="separator">/</span>
      <span class="current"><?= e($crumb ?: $title) ?></span>
    </div>
<?php
    start_session();
    foreach ($_SESSION['flash'] ?? [] as [$type, $msg]) {
        $cls = ['success' => 'badge-success', 'error' => 'badge-danger', 'warning' => 'badge-warning'][$type] ?? 'badge-primary';
        $col = ['success' => '--success', 'error' => '--danger', 'warning' => '--warning'][$type] ?? '--primary';
        echo '<div class="card flash-msg" role="alert" style="margin-bottom:1rem;padding:1rem 1.25rem;border-left:5px solid var(' . $col . ');"><span class="badge ' . $cls . '">' . e(ucfirst($type)) . '</span> ' . e($msg) . '</div>';
    }
    unset($_SESSION['flash']);
}
function page_footer(string $extraScript = ''): void
{
    ?>
  </main>
  <footer class="site-footer">
    <div class="container footer-bottom">
      <p>&copy; 2026 StudentHub - CHARUSAT FTE.</p>
      <span class="developer-tag">Yuvraj Parmar (25DCE070)</span>
    </div>
  </footer>
  <script src="../js/main.js"></script>
<?= $extraScript ?>
</body>
</html>
<?php
}
