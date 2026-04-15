<?php
include "../includes/header.php"; 
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireLogin();

$search = $_GET['search'] ?? '';
$type   = $_GET['type'] ?? '';

$sql = "SELECT * FROM jobs WHERE status='open'";

if ($search) {
    $sql .= " AND (title LIKE :search OR location LIKE :search)";
}
if ($type) {
    $sql .= " AND type = :type";
}

$stmt = $pdo->prepare($sql);

if ($search) $stmt->bindValue(':search', "%$search%");
if ($type) $stmt->bindValue(':type', $type);

$stmt->execute();
$jobs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Browse Jobs</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
    padding: 110px 50px 50px 50px;
    position: relative;
    z-index: 1;
}

/* =========================
   GLASS FORM
========================= */
form {
    display: flex;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 16px;

    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    border: 1px solid rgba(255,255,255,0.18);
    margin-bottom: 25px;
    align-items: center;
    flex-wrap: wrap;
}

/* INPUT */
form input {
    flex: 1;
    min-width: 180px;

    padding: 12px 14px;
    border: none;
    outline: none;
    border-radius: 12px;

    background: rgba(255,255,255,0.18);
    color: #fff;
}

form input::placeholder {
    color: rgba(255,255,255,0.7);
}

/* =========================
   GLASS SELECT (FIXED)
========================= */
form select {
    flex: 1;
    min-width: 180px;

    padding: 12px 14px;
    border-radius: 12px;

    border: 1px solid rgba(255,255,255,0.18);
    outline: none;

    background: rgba(255,255,255,0.12); /* 🔥 transparent glass */
    color: #fff;

    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    cursor: pointer;
}

/* dropdown items */
form select option {
    background: #1a1a1a;
    color: #fff;
}

/* focus effect */
form input:focus,
form select:focus {
    background: rgba(255,255,255,0.22);
    box-shadow: 0 0 0 2px rgba(255,255,255,0.2);
}

/* BUTTON */
.btn {
    padding: 12px 18px;
    border-radius: 12px;
    border: none;
    background: #fff;
    color: #000;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 18px;
}

/* CARD */
.card {
    padding: 20px;
    border-radius: 16px;

    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(18px);
}

.card h3 {
    font-size: 18px;
    margin-bottom: 10px;
}

.card p {
    font-size: 12px;
    color: rgba(255,255,255,0.75);
    margin-bottom: 6px;
}

.card .btn {
    display: block;
    margin-top: 12px;
    text-align: center;
}

</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <!-- SEARCH FORM -->
    <form method="GET">

        <input type="text"
               name="search"
               placeholder="Search job title or location..."
               value="<?= htmlspecialchars($search) ?>">

        <select name="type">
            <option value="">All Types</option>
            <option value="Full-time" <?= $type=='Full-time'?'selected':'' ?>>Full-time</option>
            <option value="Part-time" <?= $type=='Part-time'?'selected':'' ?>>Part-time</option>
            <option value="Remote" <?= $type=='Remote'?'selected':'' ?>>Remote</option>
            <option value="Contract" <?= $type=='Contract'?'selected':'' ?>>Contract</option>
        </select>

        <button class="btn" type="submit">Search</button>

    </form>

    <!-- JOB GRID -->
    <div class="grid">

        <?php foreach($jobs as $job): ?>
        <div class="card">

            <h3><?= htmlspecialchars($job['title']) ?></h3>
            <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
            <p><strong>Type:</strong> <?= htmlspecialchars($job['type']) ?></p>

            <a class="btn" href="job-detail.php?id=<?= $job['id'] ?>">
                View Details
            </a>

        </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>