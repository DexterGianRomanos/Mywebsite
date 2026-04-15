<?php
include "../includes/header.php"; 
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE user_id=?");
$stmt->execute([$user_id]);
$totalApps = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Dashboard — Job Seeking</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

/* =========================
   GLASS DASHBOARD UI
========================= */

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

body {
  min-height: 100vh;
  display: flex;
  background: url("../assets/images/bg.jpg") center/cover no-repeat;
  color: #fff;
}

/* DARK OVERLAY */
body::before {
  content: "";
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(4px);
  z-index: 0;
}

/* MAIN - FIXED ALIGNMENT */
.main {
  margin-left: 260px;
  flex: 1;

  /* ✅ FIX HEADER OVERLAP */
  padding: 55px;
  padding-top: 120px;

  position: relative;
  z-index: 1;
}

/* HEADER */
.page-header {
  margin-bottom: 30px;
}

.eyebrow {
  font-size: 11px;
  letter-spacing: 0.25em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.65);
}

.page-header h1 {
  font-size: 42px;
  margin-top: 6px;
}

.subtitle {
  font-size: 13px;
  color: rgba(255,255,255,0.65);
  margin-top: 4px;
}

/* GLASS STYLE */
.glass {
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.18);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.25);
  transition: 0.25s ease;
}

.glass:hover {
  transform: translateY(-5px);
  border-color: rgba(255,255,255,0.35);
}

/* STATS */
.stats-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 18px;
  margin-bottom: 30px;
}

.stat-card {
  padding: 26px;
}

.stat-label {
  font-size: 10px;
  letter-spacing: 0.25em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.65);
}

.stat-value {
  font-size: 48px;
  font-weight: 600;
  margin-top: 10px;
}

.stat-note {
  font-size: 12px;
  color: rgba(255,255,255,0.65);
  margin-top: 10px;
}

/* ACTIONS */
.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 18px;
}

.action-card {
  display: flex;
  gap: 15px;
  padding: 20px;
  text-decoration: none;
  color: #fff;
}

.action-icon {
  width: 45px;
  height: 45px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255,255,255,0.12);
  font-size: 18px;
}

.action-text strong {
  font-size: 12px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
}

.action-text p {
  font-size: 12px;
  color: rgba(255,255,255,0.65);
  margin-top: 5px;
}

/* NOTICE */
.notice {
  margin-top: 25px;
  padding: 18px 22px;
  border-left: 4px solid #fff;
}

/* APPLY GLASS */
.stat-card,
.action-card,
.notice {
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.18);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.25);
}

</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<main class="main">

  <!-- HEADER -->
  <div class="page-header">
    <span class="eyebrow">Overview</span>
    <h1>Welcome Back</h1>
    <p class="subtitle">Your activity summary dashboard</p>
  </div>

  <!-- STATS -->
  <div class="stats-row">

    <div class="stat-card glass">
      <div class="stat-label">Applications</div>
      <div class="stat-value"><?= htmlspecialchars($totalApps) ?></div>
      <div class="stat-note">Jobs you've applied to</div>
    </div>

    <div class="stat-card glass">
      <div class="stat-label">Account</div>
      <div class="stat-value" style="font-size:24px;">ACTIVE</div>
      <div class="stat-note">Profile status</div>
    </div>

    <div class="stat-card glass">
      <div class="stat-label">Jobs</div>
      <div class="stat-value">—</div>
      <div class="stat-note">Available listings</div>
    </div>

  </div>

  <!-- ACTIONS -->
  <div class="actions-grid">

    <a href="browse-jobs.php" class="action-card glass">
      <div class="action-icon">🔍</div>
      <div class="action-text">
        <strong>Browse Jobs</strong>
        <p>Find opportunities that match your skills</p>
      </div>
    </a>

    <a href="my-applications.php" class="action-card glass">
      <div class="action-icon">📄</div>
      <div class="action-text">
        <strong>Applications</strong>
        <p>Track your application status</p>
      </div>
    </a>

  </div>

  <!-- NOTICE -->
  <div class="notice glass">
    <p>💡 <strong>Tip:</strong> Keep your profile updated to improve hiring chances.</p>
  </div>

</main>

</body>
</html>