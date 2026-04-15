<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit();
} else {
    header("Location: user/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/style.css">
<title>Job Portal</title>
</head>
<body>

<h1>Available Jobs</h1>

<div class="grid">
<?php foreach($jobs as $job): ?>
<div class="card">
    <h3><?= $job['title'] ?></h3>
    <p><?= $job['company'] ?></p>
    <p><?= $job['location'] ?></p>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="user/job-detail.php?id=<?= $job['id'] ?>">View & Apply</a>
    <?php else: ?>
        <a href="login.php">Login to Apply</a>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</div>

</body>
</html>