<?php
session_start();
require_once "includes/db.php";
require_once "includes/functions.php";

$errors = [];

/* =========================
   🔥 INSTANT ADMIN LOGIN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auto_admin_login'])) {

    $stmt = $pdo->prepare("SELECT * FROM users WHERE role='admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['role']    = 'admin';
        $_SESSION['login_time'] = time();

        header("Location: admin/dashboard.php");
        exit();

    } else {
        $errors[] = "No admin account found.";
    }
}

/* =========================
   LOGIN HANDLER
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    if (!verify_csrf($_POST['csrf'])) {
        die("Invalid CSRF token");
    }

    $email      = sanitize($_POST['email']);
    $pass       = $_POST['password'];
    $login_type = $_POST['login_type'] ?? 'user';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $errors[] = "Invalid login credentials.";
    } else {

        if ($login_type === 'admin' && $user['role'] !== 'admin') {
            $errors[] = "Access denied. Not an admin account.";
        }

        elseif ($user['login_attempts'] >= 5) {
            $errors[] = "Account locked due to too many attempts.";
        }

        else {
            if (password_verify($pass, $user['password'])) {

                $pdo->prepare("UPDATE users SET login_attempts=0 WHERE id=?")
                    ->execute([$user['id']]);

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['login_time'] = time();

                header("Location: " . ($user['role'] === 'admin'
                    ? "admin/dashboard.php"
                    : "user/dashboard.php"));
                exit();

            } else {
                $pdo->prepare("UPDATE users SET login_attempts = login_attempts + 1 WHERE id=?")
                    ->execute([$user['id']]);

                $errors[] = "Invalid login credentials.";
            }
        }
    }
}

/* =========================
   REGISTER HANDLER
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    if (!verify_csrf($_POST['csrf'])) {
        die("Invalid CSRF token");
    }

    $name  = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $check->execute([$email]);

    if ($check->fetch()) {
        $errors[] = "Email already exists.";
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO users (full_name, email, password, role, login_attempts)
            VALUES (?, ?, ?, 'user', 0)
        ");

        $stmt->execute([$name, $email, $pass]);

        header("Location: login.php?registered=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Job Seeking — Login</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
* {
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:Inter;
}

body {
  min-height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  background:url("assets/images/bg.jpg") no-repeat center center/cover;
  position:relative;
}

body::before {
  content:"";
  position:absolute;
  inset:0;
  background:rgba(0,0,0,0.55);
  z-index:0;
}

.card {
  position:relative;
  z-index:1;
  width:420px;
  border-radius:16px;
  overflow:hidden;
  background:rgba(255,255,255,0.10);
  backdrop-filter:blur(18px);
  border:1px solid rgba(255,255,255,0.2);
  box-shadow:0 10px 40px rgba(0,0,0,0.3);
}

.card-header {
  padding:2rem;
  text-align:center;
  border-bottom:1px solid rgba(255,255,255,0.15);
}

.brand-label {
  font-size:11px;
  color:#fff;
  opacity:0.8;
}

.brand-sub {
  font-size:18px;
  font-weight:600;
  color:#fff;
}

.tabs {
  display:flex;
  border-bottom:1px solid rgba(255,255,255,0.15);
}

.tab-btn {
  flex:1;
  padding:12px;
  background:none;
  border:none;
  color:rgba(255,255,255,0.6);
  cursor:pointer;
}

.tab-btn.active {
  color:#fff;
  border-bottom:2px solid #fff;
}

.form-wrap {
  padding:1.8rem 2rem;
}

.field {
  margin-bottom:12px;
}

.field label {
  font-size:12px;
  color:rgba(255,255,255,0.8);
  display:block;
  margin-bottom:6px;
}

.field input {
  width:100%;
  padding:10px;
  border-radius:8px;
  border:1px solid rgba(255,255,255,0.2);
  background:rgba(255,255,255,0.08);
  color:#fff;
}

.submit-btn {
  width:100%;
  padding:12px;
  border:none;
  border-radius:10px;
  background:#fff;
  cursor:pointer;
  margin-top:10px;
}

.admin-btn {
  background:#ffd580;
}

.panel { display:none; }
.panel.active { display:block; }

.error-box {
  background:rgba(255,0,0,0.1);
  color:#ffb3b3;
  padding:8px;
  margin-bottom:10px;
  border-radius:6px;
  font-size:12px;
}
</style>
</head>

<body>

<div class="card">

  <div class="card-header">
    <div class="brand-label">JOB SEEKING</div>
    <div class="brand-sub">Login System</div>
  </div>

  <div class="tabs">
    <button class="tab-btn active" onclick="tab('login',this)">User</button>
    <button class="tab-btn" onclick="tab('register',this)">Register</button>
    <button class="tab-btn" onclick="tab('admin',this)">Admin</button>
  </div>

  <div class="form-wrap">

    <?php foreach($errors as $e): ?>
      <div class="error-box"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <!-- USER LOGIN -->
    <div id="login" class="panel active">
      <form method="POST">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="login_type" value="user">
        <input type="hidden" name="login" value="1">

        <div class="field">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>

        <div class="field">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>

        <button class="submit-btn">Login</button>
      </form>
    </div>

    <!-- REGISTER -->
    <div id="register" class="panel">
      <form method="POST">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="register" value="1">

        <div class="field">
          <label>Full Name</label>
          <input type="text" name="full_name" required>
        </div>

        <div class="field">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>

        <div class="field">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>

        <button class="submit-btn">Create Account</button>
      </form>
    </div>

    <!-- ADMIN -->
    <div id="admin" class="panel">

      <form method="POST">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="auto_admin_login" value="1">

        <button class="submit-btn admin-btn">
          ⚡ Instant Admin Login
        </button>
      </form>

      <br>

      <form method="POST">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="login_type" value="admin">
        <input type="hidden" name="login" value="1">

        <div class="field">
          <label>Admin Email</label>
          <input type="email" name="email">
        </div>

        <div class="field">
          <label>Password</label>
          <input type="password" name="password">
        </div>

        <button class="submit-btn admin-btn">Login as Admin</button>
      </form>

    </div>

  </div>
</div>

<script>
function tab(id,btn){
  document.querySelectorAll('.panel').forEach(p=>p.classList.remove('active'));
  document.getElementById(id).classList.add('active');

  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
}
</script>

</body>
</html>