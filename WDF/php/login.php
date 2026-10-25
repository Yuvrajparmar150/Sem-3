<?php
/** Practical 10: Secure login - password_verify(), session_regenerate_id(), role redirect, remember-me, last login */
require_once __DIR__ . '/layout.php';
start_session();
if (current_user()) { redirect($_SESSION['user']['role'] === 'admin' ? 'admin_dashboard.php' : 'my_dashboard.php'); }

$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';
    if (!csrf_valid($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid form token. Reload and try again.';
    } else {
        $st = db()->prepare('SELECT * FROM users WHERE email = ?');
        $st->execute([$email]);
        $u = $st->fetch();
        if (!$u || !password_verify($pass, $u['password_hash'])) {
            $error = 'Invalid email or password.';
            audit('LOGIN_FAILED', 'Failed login for ' . $email, $u['id'] ?? null);
        } elseif ($u['status'] !== 'active') {
            $error = 'Your account is inactive. Contact the administrator.';
            audit('LOGIN_BLOCKED', 'Inactive account tried to log in: ' . $email, (int)$u['id']);
        } else {
            login_user($u, !empty($_POST['remember']));
            audit('LOGIN', 'User logged in', (int)$u['id']);
            redirect($u['role'] === 'admin' ? 'admin_dashboard.php' : 'my_dashboard.php');
        }
    }
}
$notice = '';
if (!empty($_GET['timeout']))     { $notice = ['warning', 'Your session expired due to inactivity. Please log in again.']; }
elseif (!empty($_GET['loggedout'])) { $notice = ['success', 'You have been logged out successfully.']; }
elseif (!empty($_GET['denied']))    { $notice = ['error', 'Please log in to access that page.']; }
if ($notice) { flash($notice[0], $notice[1]); }

page_header('Secure Login', 'Practical 10', 'Secure Login (Practical 10)');
?>
<div style="max-width:460px;margin:0 auto;">
  <div class="card" style="padding:2.25rem;box-shadow:var(--shadow-lg);">
    <div style="text-align:center;margin-bottom:1.5rem;">
      <div class="logo-icon" style="margin:0 auto 1rem;width:50px;height:50px;font-size:1.5rem;">🎓</div>
      <h2>Sign In to Portal</h2>
      <p style="margin-bottom:0;">PHP sessions · password_verify() · role-based redirect</p>
    </div>
    <?php if ($error): ?><div class="card" role="alert" style="margin-bottom:1rem;padding:.75rem 1rem;border-left:5px solid var(--danger);"><span class="badge badge-danger">Error</span> <?= e($error) ?></div><?php endif; ?>
    <form method="post" action="login.php">
      <?= csrf_field() ?>
      <div class="form-group">
        <label class="form-label" for="email">Email <span class="required">*</span></label>
        <input class="form-control" type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="25dce070@charusat.edu.in" required>
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Password <span class="required">*</span></label>
        <input class="form-control" type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label class="checkbox-group"><input type="checkbox" name="remember" value="1"><span>Remember me for 30 days</span></label>
      </div>
      <button class="btn btn-primary btn-lg btn-block" type="submit">Sign In →</button>
    </form>
    <p style="margin:1rem 0 0;text-align:center;font-size:.9rem;">Demo: <code>admin@charusat.edu.in / Admin@2026</code><br><code>25dce070@charusat.edu.in / Student@2026</code></p>
    <p style="margin:.75rem 0 0;text-align:center;">No account? <a href="register_db.php"><strong>Register</strong></a></p>
  </div>
</div>
<?php page_footer();
