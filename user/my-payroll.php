<?php
include "../includes/header.php"; 
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT p.*, j.title
    FROM payroll p
    JOIN jobs j ON j.id = p.job_id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$user_id]);
$payrolls = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Payroll</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: url("../assets/images/bg.jpg") center/cover;
    color: white;
}

/* DARK OVERLAY */
body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(3px);
}

/* MAIN CONTAINER (FIXED HEADER SPACE) */
.container {
    margin-left: 260px;

    /* ✅ IMPORTANT FIX FOR HEADER */
    padding: 100px 40px 40px 40px;

    position: relative;
    z-index: 1;
    width: calc(100% - 260px);
}

/* GLASS CARD */
.card {
    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(18px);
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

/* TITLE */
h2 {
    margin-bottom: 10px;
    font-size: 22px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

/* HEADER */
th {
    padding: 14px;
    text-align: left;
    background: rgba(0,0,0,0.4);
    font-size: 13px;
}

/* CELLS */
td {
    padding: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.12);
    font-size: 14px;
    vertical-align: middle;
}

/* ALIGN */
.col-money {
    text-align: right;
    font-family: monospace;
}

.col-center {
    text-align: center;
}

/* BADGE */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    display: inline-block;
    color: white;
    text-transform: capitalize;
}

/* EMPTY STATE */
.empty {
    text-align: center;
    padding: 20px;
    color: #ddd;
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="container">

<div class="card">

<h2>💰 My Payroll</h2>

<table>
<tr>
    <th>Job</th>
    <th>Basic</th>
    <th>Allowance</th>
    <th>Deduction</th>
    <th>Net Salary</th>
    <th>Status</th>
</tr>

<?php if (count($payrolls) > 0): ?>

    <?php foreach($payrolls as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p['title']) ?></td>

        <td class="col-money">₱<?= number_format($p['basic_salary'], 2) ?></td>
        <td class="col-money">₱<?= number_format($p['allowance'], 2) ?></td>
        <td class="col-money">₱<?= number_format($p['deduction'], 2) ?></td>
        <td class="col-money"><b>₱<?= number_format($p['net_salary'], 2) ?></b></td>

        <td class="col-center">
            <span class="badge" style="background:<?= $p['status']=='paid'?'#22c55e':'#f59e0b' ?>">
                <?= htmlspecialchars($p['status']) ?>
            </span>
        </td>
    </tr>
    <?php endforeach; ?>

<?php else: ?>
    <tr>
        <td colspan="6" class="empty">
            No payroll records yet.
        </td>
    </tr>
<?php endif; ?>

</table>

</div>

</div>

</body>
</html>