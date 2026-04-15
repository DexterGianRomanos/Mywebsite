<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireLogin();

$user_id = $_SESSION['user_id'];

/* GET USER DATA */
$stmt = $pdo->prepare("SELECT full_name, profile_pic FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$fullName = $user['full_name'] ?? 'User';
$profilePic = $user['profile_pic'] ?? 'default.png';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Jost:wght@300;400;500&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* SIDEBAR */
.sidebar {
  width: 260px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  background: #0f0f0f;
  border-right: 1px solid rgba(255,255,255,0.06);
  display: flex;
  flex-direction: column;
}

/* PROFILE */
.profile-box {
  padding: 30px 20px;
  text-align: center;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}

.profile-box img {
  width: 85px;
  height: 85px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(255,255,255,0.15);
}

.profile-box .name {
  margin-top: 10px;
  font-size: 13px;
  color: #fff;
  letter-spacing: 1px;
}

/* NAV */
.sidebar-nav {
  flex: 1;
  padding: 20px 0;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.sidebar-nav a {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 12px 26px;
  font-size: 12px;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: #9a9a9a;
  text-decoration: none;
  transition: 0.25s;
  position: relative;
}

.sidebar-nav a:hover {
  color: #fff;
  background: rgba(255,255,255,0.04);
  padding-left: 32px;
}

.sidebar-nav a::before {
  content: '';
  position: absolute;
  left: 0;
  width: 2px;
  height: 60%;
  background: #fff;
  transform: scaleY(0);
  transition: 0.25s;
}

.sidebar-nav a:hover::before {
  transform: scaleY(1);
}

/* ICON */
.nav-icon {
  width: 16px;
  height: 16px;
  opacity: 0.7;
}

.sidebar-nav a:hover .nav-icon {
  opacity: 1;
}

/* FOOTER */
.sidebar-footer {
  padding: 20px 26px;
  border-top: 1px solid rgba(255,255,255,0.06);
}

.sidebar-footer a {
  display: flex;
  align-items: center;
  gap: 14px;
  font-size: 11px;
  letter-spacing: 0.2em;
  color: #888;
  text-transform: uppercase;
  text-decoration: none;
  transition: 0.2s;
}

.sidebar-footer a:hover {
  color: #fff;
}
</style>

<div class="sidebar">

  <!-- PROFILE -->
  <div class="profile-box">
    <img src="../uploads/<?= htmlspecialchars($profilePic) ?>"
         onerror="this.src='../uploads/default.png'">

    <div class="name">
      <?= htmlspecialchars($fullName) ?>
    </div>
  </div>

  <!-- NAVIGATION (ICONS KEPT) -->
  <nav class="sidebar-nav">

    <a href="dashboard.php">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
      </svg>
      Dashboard
    </a>

    <a href="browse-jobs.php">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <rect x="2" y="7" width="20" height="14" rx="2"/>
      </svg>
      Jobs
    </a>

    <a href="my-applications.php">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
      </svg>
      Applications
    </a>

    <a href="my-payroll.php">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
      </svg>
      Payroll
    </a>

    <a href="profile.php">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <circle cx="12" cy="7" r="4"/>
      </svg>
      Profile
    </a>

  </nav>

  <!-- FOOTER -->
  <div class="sidebar-footer">
    <a href="../logout.php">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
      </svg>
      Logout
    </a>
  </div>

</div>