<?php
/**
 * Practical 9: Secure registration - validation, duplicate check, password_hash(), prepared INSERT, CSRF, audit log
 */
require_once __DIR__ . '/layout.php';
start_session();
$errors = [];
$old = ['full_name' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';
    $conf  = $_POST['confirm_password'] ?? '';
    $old   = ['full_name' => $name, 'email' => $email];

    if (!csrf_valid($_POST['csrf_token'] ?? null)) { $errors['csrf'] = 'Invalid or expired form token. Reload the page and try again.'; }
    if (!preg_match('/^[A-Za-z][A-Za-z\s]{2,49}$/', $name)) { $errors['full_name'] = 'Name must be 3-50 letters and spaces.'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 120) { $errors['email'] = 'Enter a valid email address.'; }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $pass)) { $errors['password'] = 'Password needs 8+ characters with upper, lower, number and symbol.'; }
    if ($pass !== $conf) { $errors['confirm_password'] = 'Passwords do not match.'; }

    if (!$errors) {
        $chk = db()->prepare('SELECT id FROM users WHERE email = ?');
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $errors['email'] = 'This email is already registered. Try logging in instead.';
            audit('REGISTER_DUPLICATE', 'Duplicate registration attempt: ' . $email, null);
        }
    }
    if (!$errors) {
        try {
            $ins = db()->prepare("INSERT INTO users (full_name, email, password_hash, role, status) VALUES (?, ?, ?, 'student', 'active')");
            $ins->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
            audit('REGISTER', 'New user registered: ' . $email, (int)db()->lastInsertId());
            flash('success', 'Registration successful! You can now log in.');
            redirect('login.php');
        } catch (PDOException $ex) {
            error_log('[StudentHub] register failed: ' . $ex->getMessage());
            $errors['db'] = 'Could not complete registration. Please try again.';
        }
    }
}
page_header('Secure Registration', 'Practical 9', 'Secure Registration (Practical 9)');
?>
<div style="max-width:520px;margin:0 auto;">
  <div class="card" style="padding:2rem;">
    <span class="badge badge-primary">Practical 9</span>
    <h2>Create your StudentHub account</h2>
    <?php foreach (['csrf', 'db'] as $k) if (isset($errors[$k])): ?><p class="invalid-feedback" style="display:block;"><?= e($errors[$k]) ?></p><?php endif; ?>
    <form method="post" action="register_db.php" novalidate>
      <?= csrf_field() ?>
      <div class="form-group">
        <label class="form-label" for="full_name">Full name <span class="required">*</span></label>
        <input class="form-control<?= isset($errors['full_name']) ? ' is-invalid' : '' ?>" type="text" id="full_name" name="full_name" value="<?= e($old['full_name']) ?>" required>
        <?php if (isset($errors['full_name'])): ?><div class="invalid-feedback" style="display:block;"><?= e($errors['full_name']) ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label class="form-label" for="email">Email <span class="required">*</span></label>
        <input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" type="email" id="email" name="email" value="<?= e($old['email']) ?>" required>
        <?php if (isset($errors['email'])): ?><div class="invalid-feedback" style="display:block;"><?= e($errors['email']) ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Password <span class="required">*</span></label>
        <input class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" type="password" id="password" name="password" required>
        <?php if (isset($errors['password'])): ?><div class="invalid-feedback" style="display:block;"><?= e($errors['password']) ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label class="form-label" for="confirm_password">Confirm password <span class="required">*</span></label>
        <input class="form-control<?= isset($errors['confirm_password']) ? ' is-invalid' : '' ?>" type="password" id="confirm_password" name="confirm_password" required>
        <?php if (isset($errors['confirm_password'])): ?><div class="invalid-feedback" style="display:block;"><?= e($errors['confirm_password']) ?></div><?php endif; ?>
      </div>
      <button class="btn btn-primary btn-lg btn-block" type="submit">Register securely →</button>
    </form>
    <p style="margin-top:1rem;margin-bottom:0;text-align:center;">Already registered? <a href="login.php"><strong>Login</strong></a></p>
  </div>
</div>
<?php page_footer();
