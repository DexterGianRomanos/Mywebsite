<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireAdmin();

/* DELETE JOB */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM jobs WHERE id=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: manage-jobs.php");
    exit();
}

/* FETCH JOBS */
$jobs = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Jobs</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

/* BACKGROUND */
body {
  min-height: 100vh;
  background: url("../assets/images/bg.jpg") no-repeat center center/cover;
  position: relative;
}

/* OVERLAY */
body::before {
  content: "";
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(2px);
  z-index: 0;
}

/* SIDEBAR FIX */
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: 260px;
  height: 100vh;
  z-index: 2;
}

/* MAIN */
.main {
  margin-left: 260px;
  padding: 40px;
  position: relative;
  z-index: 1;
  color: #fff;
}

/* TOP BUTTON */
.top-bar {
  margin-bottom: 20px;
}

.btn {
  display: inline-block;
  padding: 8px 12px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 13px;
  background: #fff;
  color: #000;
  margin-right: 5px;
}

.btn:hover {
  background: #eaeaea;
}

/* DELETE BUTTON */
.btn-danger {
  background: #ff4d4d;
  color: #fff;
}

.btn-danger:hover {
  background: #e60000;
}

/* TABLE */
table {
  width: 100%;
  border-collapse: collapse;

  background: rgba(255,255,255,0.10);
  backdrop-filter: blur(18px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 12px;
  overflow: hidden;
}

th {
  text-align: left;
  padding: 12px;
  background: rgba(255,255,255,0.15);
  color: #fff;
  font-weight: 600;
}

td {
  padding: 12px;
  border-top: 1px solid rgba(255,255,255,0.1);
  color: #fff;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .main {
    margin-left: 0;
  }
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main">

  <div class="top-bar">
    <a class="btn" href="job-form.php">+ Create Job</a>
  </div>

  <table>
    <tr>
      <th>Title</th>
      <th>Company</th>
      <th>Status</th>
      <th>Action</th>
    </tr>

    <?php foreach($jobs as $j): ?>
    <tr>
      <td><?= htmlspecialchars($j['title']) ?></td>
      <td><?= htmlspecialchars($j['company']) ?></td>
      <td><?= htmlspecialchars($j['status']) ?></td>
      <td>
        <a class="btn" href="job-form.php?id=<?= $j['id'] ?>">Edit</a>
        <a class="btn btn-danger"
           onclick="return confirm('Delete this job?')"
           href="?delete=<?= $j['id'] ?>">
           Delete
        </a>
      </td>
    </tr>
    <?php endforeach; ?>

  </table>

</div>

</body>
</html>