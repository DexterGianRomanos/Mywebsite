<?php
include "../includes/header.php"; 
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireAdmin();

/* =========================
   UPDATE SALARY (EDIT MODE)
========================= */
if (isset($_POST['update_salary'])) {

    $id = $_POST['id'];
    $basic = $_POST['basic_salary'];
    $allowance = $_POST['allowance'];
    $deduction = $_POST['deduction'];

    $net = $basic + $allowance - $deduction;

    $stmt = $pdo->prepare("
        UPDATE payroll 
        SET basic_salary=?, allowance=?, deduction=?, net_salary=? 
        WHERE id=?
    ");
    $stmt->execute([$basic, $allowance, $deduction, $net, $id]);

    header("Location: payroll.php");
    exit();
}

/* =========================
   TOGGLE PAID / PENDING
========================= */
if (isset($_POST['pay_id'])) {

    $check = $pdo->prepare("SELECT status FROM payroll WHERE id=?");
    $check->execute([$_POST['pay_id']]);
    $current = $check->fetchColumn();

    $newStatus = ($current === 'paid') ? 'pending' : 'paid';

    $stmt = $pdo->prepare("UPDATE payroll SET status=? WHERE id=?");
    $stmt->execute([$newStatus, $_POST['pay_id']]);

    header("Location: payroll.php");
    exit();
}

/* =========================
   FETCH PAYROLL
========================= */
$stmt = $pdo->prepare("
    SELECT p.*, u.full_name, j.title
    FROM payroll p
    JOIN users u ON u.id = p.user_id
    JOIN jobs j ON j.id = p.job_id
    ORDER BY p.created_at DESC
");
$stmt->execute();
$payrolls = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Payroll System</title>

<style>
body {
    margin:0;
    font-family: Arial;
    background: url("../assets/images/bg.jpg") center/cover no-repeat;
    color:white;
}

body::before{
    content:"";
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.55);
}

.container{
    margin-left:260px;
    padding:40px;
    position:relative;
    z-index:1;
}

table{
    width:100%;
    border-collapse:collapse;
    background:rgba(255,255,255,0.10);
    backdrop-filter: blur(18px);
    border-radius:12px;
    overflow:hidden;
}

th,td{
    padding:12px;
    border-bottom:1px solid rgba(255,255,255,0.15);
}

th{
    background:rgba(0,0,0,0.5);
}

.btn{
    padding:6px 10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.btn-paid{
    background:#22c55e;
    color:white;
}

.btn-edit{
    background:#3b82f6;
    color:white;
}

.edit-box{
    background:rgba(255,255,255,0.12);
    padding:10px;
    margin-top:10px;
    border-radius:10px;
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="container">

<h2>Payroll Management</h2>

<table>
<tr>
    <th>Employee</th>
    <th>Job</th>
    <th>Basic</th>
    <th>Allowance</th>
    <th>Deduction</th>
    <th>Net</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach($payrolls as $p): ?>
<tr>
    <td><?= $p['full_name'] ?></td>
    <td><?= $p['title'] ?></td>
    <td>₱<?= $p['basic_salary'] ?></td>
    <td>₱<?= $p['allowance'] ?></td>
    <td>₱<?= $p['deduction'] ?></td>
    <td><b>₱<?= $p['net_salary'] ?></b></td>

    <td>
        <span style="padding:5px 10px;border-radius:20px;background:<?= $p['status']=='paid'?'#22c55e':'#f59e0b' ?>">
            <?= $p['status'] ?>
        </span>
    </td>

    <td>

        <!-- PAY BUTTON -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="pay_id" value="<?= $p['id'] ?>">
            <button class="btn btn-paid">Toggle Paid</button>
        </form>

        <!-- EDIT BUTTON (SHOW FORM BELOW) -->
        <details style="margin-top:5px;">
            <summary class="btn btn-edit">Edit Salary</summary>

            <div class="edit-box">
                <form method="POST">

                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                    <input type="number" name="basic_salary" value="<?= $p['basic_salary'] ?>" placeholder="Basic"><br><br>

                    <input type="number" name="allowance" value="<?= $p['allowance'] ?>" placeholder="Allowance"><br><br>

                    <input type="number" name="deduction" value="<?= $p['deduction'] ?>" placeholder="Deduction"><br><br>

                    <button class="btn btn-edit" name="update_salary">Save</button>

                </form>
            </div>

        </details>

    </td>
</tr>
<?php endforeach; ?>

</table>

</div>

</body>
</html>