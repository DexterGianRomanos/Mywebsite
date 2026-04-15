<?php
require_once "includes/db.php";
require_once "includes/functions.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'])) {
        die("Invalid CSRF token");
    }

    $name  = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $pass  = $_POST['password'];

    if (!$name || !$email || !$pass) {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (strlen($pass) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users(full_name,email,password) VALUES(?,?,?)");
        try {
            $stmt->execute([$name, $email, $hash]);
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "That email address is already registered.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Job Seeking — Create Account</title>
<link rel="stylesheet" href="assets/style.css">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; background: #f4f6fb; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
.card { width: 420px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
.card-header { padding: 1.75rem 2rem 1.5rem; border-bottom: 1px solid #e2e8f0; text-align: center; }
.brand-icon { width: 46px; height: 46px; background: #185FA5; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.85rem; }
.brand-icon svg { width: 22px; height: 22px; fill: white; }
.brand-label { font-size: 11px; font-weight: 600; letter-spacing: 0.13em; text-transform: uppercase; color: #185FA5; margin-bottom: 4px; }
.brand-sub { font-size: 17px; font-weight: 600; color: #1a202c; }
.form-wrap { padding: 1.75rem 2rem 2rem; }
.error-box { background: #fff5f5; color: #c53030; font-size: 13px; border: 1px solid #fed7d7; border-radius: 8px; padding: 9px 12px; margin-bottom: 14px; }
.field { margin-bottom: 14px; }
.field label { display: block; font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 6px; letter-spacing: 0.04em; }
.field input { width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e0; border-radius: 8px; background: #fff; color: #1a202c; outline: none; transition: border-color 0.2s; }
.field input:focus { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,0.1); }
.pw-wrap { position: relative; }
.pw-wrap input { padding-right: 40px; }
.eye-btn { position: absolute; right: 11px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #a0aec0; display: flex; align-items: center; }
.eye-btn svg { width: 16px; height: 16px; }
.submit-btn { width: 100%; padding: 11px; margin-top: 6px; font-size: 14px; font-weight: 600; color: #fff; background: #185FA5; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; }
.submit-btn:hover { background: #0C447C; }
.login-link { text-align: center; margin-top: 1.25rem; font-size: 13px; color: #718096; }
.login-link a { color: #185FA5; text-decoration: none; font-weight: 600; }
.login-link a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="card">
  <div class="card-header">
    <div class="brand-icon">
      <svg viewBox="0 0 24 24"><path d="M20 7H16V5C16 3.9 15.1 3 14 3H10C8.9 3 8 3.9 8 5V7H4C2.9 7 2 7.9 2 9V19C2 20.1 2.9 21 4 21H20C21.1 21 22 20.1 22 19V9C22 7.9 21.1 7 20 7ZM10 5H14V7H10V5ZM20 19H4V9H20V19Z"/></svg>
    </div>
    <div class="brand-label">Job Seeking</div>
    <div class="brand-sub">Create your account</div>
  </div>

  <div class="form-wrap">

    <?php foreach ($errors as $e): ?>
      <div class="error-box"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

      <div class="field">
        <label>Full name</label>
        <input type="text" name="name" placeholder="Jane Doe" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
      </div>

      <div class="field">
        <label>Email address</label>
        <input type="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="pw-wrap">
          <input type="password" name="password" id="password" placeholder="At least 6 characters" required>
          <button class="eye-btn" type="button" onclick="togglePass()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <button class="submit-btn" type="submit">Create account</button>
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Sign in</a></p>
  </div>
</div>

<script>
function togglePass() {
  const el = document.getElementById('password');
  el.type = el.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>