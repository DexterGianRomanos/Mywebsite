<?php
include "../includes/header.php"; 
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireLogin();

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id=?");
$stmt->execute([$id]);
$job = $stmt->fetch();

if (!$job) {
    die("Job not found");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Job Details</title>

<style>
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

/* MAIN */
.main {
    margin-left: 260px;
    flex: 1;
    padding: 60px;
    position: relative;
    z-index: 1;
}

/* GLASS CARD */
.card {
    max-width: 700px;
    padding: 30px;
    border-radius: 16px;

    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    box-shadow: 0 8px 30px rgba(0,0,0,0.25);
}

/* TEXT */
h2 {
    font-size: 28px;
    margin-bottom: 10px;
}

p {
    font-size: 14px;
    color: rgba(255,255,255,0.75);
    margin-bottom: 10px;
    line-height: 1.6;
}

/* BUTTON */
.btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    border-radius: 10px;
    background: #fff;
    color: #000;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s ease;
}

.btn:hover {
    background: #eaeaea;
    transform: translateY(-2px);
}

</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <div class="card">

        <h2><?= htmlspecialchars($job['title']) ?></h2>

        <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>

        <p><strong>Description:</strong><br>
            <?= nl2br(htmlspecialchars($job['description'])) ?>
        </p>

        <p><strong>Requirements:</strong><br>
            <?= nl2br(htmlspecialchars($job['requirements'])) ?>
        </p>

        <a class="btn" href="apply.php?id=<?= $job['id'] ?>">
            Apply Now
        </a>

    </div>

</div>

</body>
</html>