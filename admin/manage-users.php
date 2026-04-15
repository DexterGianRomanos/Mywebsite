<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireAdmin();

/* DELETE USER */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: manage-users.php");
    exit();
}

/* FETCH USERS */
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>

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

/* MAIN CONTENT */
.main {
  margin-left: 260px;
  padding: 40px;
  position: relative;
  z-index: 1;
  color: #fff;
}

/* TITLE */
h2 {
  margin-bottom: 20px;
  font-size: 24px;
}

/* TABLE GLASS STYLE */
table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255,255,255,0.10);
  backdrop-filter: blur(18px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 12px;
  overflow: hidden;
}

/* HEADER */
th {
  text-align: left;
  padding: 12px;
  background: rgba(255,255,255,0.15);
  color: #fff;
  font-weight: 600;
}

/* ROWS */
td {
  padding: 12px;
  color: #fff;
  border-top: 1px solid rgba(255,255,255,0.1);
}

/* DELETE BUTTON */
.btn {
  padding: 6px 10px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
}

.btn-danger {
  background: #ff4d4d;
  color: #fff;
}

.btn-danger:hover {
  background: #e60000;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .main {
    margin-left: 0;
  }

  table {
    font-size: 13px;
  }
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main">

<h2>Manage Users</h2>

<table>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Action</th>
  </tr>

  <?php foreach($users as $u): ?>
  <tr>
    <td><?= htmlspecialchars($u['full_name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['role']) ?></td>
    <td>
      <a class="btn btn-danger"
         onclick="return confirm('Delete this user?')"
         href="?delete=<?= $u['id'] ?>">
         Delete
      </a>
    </td>
  </tr>
  <?php endforeach; ?>

</table>

</div>

</body>
</html>