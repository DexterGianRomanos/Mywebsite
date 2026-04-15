<?php

require_once "../includes/db.php";
require_once "../includes/auth.php";
requireAdmin();

$id = $_GET['id'] ?? null;
$job = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id=?");
    $stmt->execute([$id]);
    $job = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title    = $_POST['title'];
    $company  = $_POST['company'];
    $location = $_POST['location'];
    $type     = $_POST['type'];
    $salary   = $_POST['salary'];
    $desc     = $_POST['desc'];
    $req      = $_POST['req'];
    $slots    = $_POST['slots'];
    $status   = $_POST['status'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE jobs 
            SET title=?, company=?, location=?, type=?, salary_range=?, description=?, requirements=?, slots=?, status=? 
            WHERE id=?");

        $stmt->execute([$title,$company,$location,$type,$salary,$desc,$req,$slots,$status,$id]);

    } else {
        $stmt = $pdo->prepare("INSERT INTO jobs
        (title, company, location, type, salary_range, description, requirements, slots, status, posted_by)
        VALUES (?,?,?,?,?,?,?,?,?,?)");

        $stmt->execute([$title,$company,$location,$type,$salary,$desc,$req,$slots,$status,$_SESSION['user_id']]);
    }

    header("Location: manage-jobs.php");
    exit();
}
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

/* DARK OVERLAY */
body::before {
  content: "";
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(2px);
  z-index: 0;
}

/* ================= SIDEBAR FIX ================= */
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: 260px;
  height: 100vh;
  z-index: 2;
}

/* ================= MAIN FIX ================= */
.main {
  margin-left: 260px; /* IMPORTANT FIX */
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

/* FORM */
form {
  max-width: 700px;
  background: rgba(255,255,255,0.10);
  backdrop-filter: blur(18px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 16px;
  padding: 25px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.25);

  display: flex;
  flex-direction: column;
  gap: 12px;
}

/* INPUTS */
input, textarea, select {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,0.2);
  background: rgba(255,255,255,0.08);
  color: #fff;
  outline: none;
}

input::placeholder,
textarea::placeholder {
  color: rgba(255,255,255,0.5);
}

textarea {
  min-height: 90px;
  resize: vertical;
}

/* BUTTON */
.btn {
  padding: 12px;
  border: none;
  border-radius: 10px;
  background: #fff;
  color: #000;
  font-weight: 600;
  cursor: pointer;
}

.btn:hover {
  background: #eaeaea;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .main {
    margin-left: 0;
  }

  .sidebar {
    position: relative;
    width: 100%;
    height: auto;
  }
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main">

<h2><?= $id ? "Edit Job" : "Add Job" ?></h2>

<form method="POST">

<input name="title" placeholder="Job Title"
value="<?= $job['title'] ?? '' ?>">

<input name="company" placeholder="Company"
value="<?= $job['company'] ?? '' ?>">

<input name="location" placeholder="Location"
value="<?= $job['location'] ?? '' ?>">

<select name="type">
  <option value="Full-time" <?= ($job['type'] ?? '')=='Full-time'?'selected':'' ?>>Full-time</option>
  <option value="Part-time" <?= ($job['type'] ?? '')=='Part-time'?'selected':'' ?>>Part-time</option>
  <option value="Remote" <?= ($job['type'] ?? '')=='Remote'?'selected':'' ?>>Remote</option>
  <option value="Contract" <?= ($job['type'] ?? '')=='Contract'?'selected':'' ?>>Contract</option>
</select>

<input name="salary" placeholder="Salary Range"
value="<?= $job['salary_range'] ?? '' ?>">

<textarea name="desc" placeholder="Job Description"><?= $job['description'] ?? '' ?></textarea>

<textarea name="req" placeholder="Requirements"><?= $job['requirements'] ?? '' ?></textarea>

<input name="slots" placeholder="Slots"
value="<?= $job['slots'] ?? '' ?>">

<select name="status">
  <option value="open" <?= ($job['status'] ?? '')=='open'?'selected':'' ?>>Open</option>
  <option value="closed" <?= ($job['status'] ?? '')=='closed'?'selected':'' ?>>Closed</option>
</select>

<button class="btn">Save Job</button>

</form>

</div>

</body>
</html>