<?php
require_once "../includes/auth.php";
requireAdmin();
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

/* SIDEBAR */
.sidebar {
  width: 260px;
  min-height: 100vh;
  position: fixed;
  top: 0;
  left: 0;

  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);

  border-right: 1px solid rgba(255,255,255,0.15);

  padding: 30px 20px;
  z-index: 10;

  display: flex;
  flex-direction: column;
}

/* TITLE */
.sidebar h2 {
  color: #fff;
  font-size: 18px;
  margin-bottom: 25px;
  font-weight: 600;
  letter-spacing: 1px;
  text-transform: uppercase;
}

/* LINKS */
.sidebar a {
  display: flex;
  align-items: center;
  gap: 10px;

  padding: 12px 14px;
  margin-bottom: 8px;

  text-decoration: none;
  color: rgba(255,255,255,0.75);
  font-size: 14px;

  border-radius: 10px;
  transition: 0.25s ease;
}

/* HOVER EFFECT */
.sidebar a:hover {
  background: rgba(255,255,255,0.12);
  color: #fff;
  transform: translateX(5px);
}

/* LOGOUT SPECIAL */
.sidebar a:last-child {
  margin-top: auto;
  color: #ffb3b3;
}

.sidebar a:last-child:hover {
  background: rgba(255,0,0,0.15);
  color: #fff;
}
</style>

<div class="sidebar">
    <h2>Admin Panel</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="manage-jobs.php">💼 Jobs</a>
    <a href="applications.php">📄 Applications</a>
    <a href="manage-users.php">👥 Users</a>
    <a href="payroll.php">💰 Payroll</a>
    <hr style="margin:10px 0; opacity:0.2;">

    <a href="../logout.php">🚪 Logout</a>
</div>