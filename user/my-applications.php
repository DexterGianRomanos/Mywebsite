<?php
include "../includes/header.php"; 
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/functions.php";

requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT a.*, j.title, j.company, j.location 
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();

/* STATUS COLORS */
function statusColor($status) {
    return match($status) {
        'pending' => '#6b7280',
        'reviewed' => '#3b82f6',
        'shortlisted' => '#f59e0b',
        'rejected' => '#ef4444',
        'hired' => '#22c55e',
        default => '#6b7280'
    };
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Applications</title>

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
    color: #fff;
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

/* SIDEBAR SPACE + HEADER FIX */
.container {
    margin-left: 260px;

    /* 🔥 FIX HEADER OVERLAP */
    padding: 110px 40px 40px 40px;

    position: relative;
    z-index: 1;
}

h2 {
    margin-bottom: 20px;
    font-size: 26px;
}

/* TABLE STYLE */
.table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(18px);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

.table th {
    background: rgba(0,0,0,0.6);
    color: #fff;
    text-align: left;
    padding: 14px;
    font-size: 13px;
}

.table td {
    padding: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    font-size: 14px;
}

.table tr:hover {
    background: rgba(255,255,255,0.05);
}

/* STATUS BADGE */
.badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    color: #fff;
    text-transform: capitalize;
    display: inline-block;
}

/* RESUME LINK */
.resume-link {
    color: #4f6ef7;
    text-decoration: none;
}

.resume-link:hover {
    text-decoration: underline;
}

/* EMPTY STATE */
.empty {
    text-align: center;
    padding: 20px;
    color: rgba(255,255,255,0.7);
}

</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="container">

    <h2>My Applications</h2>

    <table class="table">
        <tr>
            <th>Job Title</th>
            <th>Company</th>
            <th>Location</th>
            <th>Status</th>
            <th>Resume</th>
            <th>Applied At</th>
        </tr>

        <?php if (count($applications) > 0): ?>
            <?php foreach($applications as $app): ?>
                <tr>
                    <td><?= htmlspecialchars($app['title']) ?></td>
                    <td><?= htmlspecialchars($app['company']) ?></td>
                    <td><?= htmlspecialchars($app['location']) ?></td>

                    <td>
                        <span class="badge"
                              style="background: <?= statusColor($app['status']) ?>;">
                            <?= htmlspecialchars($app['status']) ?>
                        </span>
                    </td>

                    <td>
                        <?php if (!empty($app['resume_path'])): ?>
                            <a class="resume-link"
                               href="../uploads/<?= htmlspecialchars($app['resume_path']) ?>"
                               target="_blank">
                                View Resume
                            </a>
                        <?php else: ?>
                            No file
                        <?php endif; ?>
                    </td>

                    <td>
                        <?= !empty($app['applied_at'])
                            ? date("M d, Y", strtotime($app['applied_at']))
                            : 'N/A' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="empty">
                    No applications yet.
                </td>
            </tr>
        <?php endif; ?>

    </table>

</div>

</body>
</html>