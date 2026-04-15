<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
requireAdmin();

/* =========================
   UPDATE STATUS + AUTO PAYROLL + NOTIFICATION
========================= */
if (isset($_POST['status'], $_POST['id'])) {

    $id = $_POST['id'];
    $status = $_POST['status'];

    // 1. Get application first
    $stmt = $pdo->prepare("SELECT * FROM applications WHERE id=?");
    $stmt->execute([$id]);
    $app = $stmt->fetch();

    if ($app) {

        // 2. Update application status
        $stmt = $pdo->prepare("
            UPDATE applications 
            SET status=?, updated_at=NOW() 
            WHERE id=?
        ");
        $stmt->execute([$status, $id]);

        /* =========================
           HIRED STATUS
        ========================= */
        if ($status === 'hired') {

            // get job salary
            $stmt = $pdo->prepare("SELECT salary_range FROM jobs WHERE id=?");
            $stmt->execute([$app['job_id']]);
            $job = $stmt->fetch();

            $salary = $job['salary_range'] ?? 0;

            // prevent duplicate payroll
            $check = $pdo->prepare("
                SELECT id FROM payroll 
                WHERE user_id=? AND job_id=?
            ");
            $check->execute([$app['user_id'], $app['job_id']]);

            if (!$check->fetch()) {

                $stmt = $pdo->prepare("
                    INSERT INTO payroll (
                        user_id,
                        job_id,
                        basic_salary,
                        allowance,
                        deduction,
                        net_salary,
                        pay_period_start,
                        pay_period_end,
                        status
                    )
                    VALUES (?, ?, ?, 0, 0, ?, CURDATE(), CURDATE(), 'pending')
                ");

                $stmt->execute([
                    $app['user_id'],
                    $app['job_id'],
                    $salary,
                    $salary
                ]);
            }

            /* =========================
               ✅ HIRED NOTIFICATION MESSAGE
            ========================= */
            $stmt = $pdo->prepare("
                INSERT INTO notifications (user_id, message)
                VALUES (?, ?)
            ");
            $stmt->execute([
                $app['user_id'],
                "🎉 Congratulations! You have been hired. Welcome to the team!"
            ]);
        }

        /* =========================
           REJECTED NOTIFICATION
        ========================= */
        if ($status === 'rejected') {

            $stmt = $pdo->prepare("
                INSERT INTO notifications (user_id, message)
                VALUES (?, ?)
            ");
            $stmt->execute([
                $app['user_id'],
                "❌ Sorry, your application was not successful this time."
            ]);
        }
    }

    header("Location: applications.php");
    exit();
}

/* =========================
   FETCH APPLICATIONS
========================= */
$app = $pdo->query("
SELECT a.*, u.full_name, j.title
FROM applications a 
JOIN users u ON u.id = a.user_id
JOIN jobs j ON j.id = a.job_id
ORDER BY a.id DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Applications</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

body {
  min-height: 100vh;
  background: url("../assets/images/bg.jpg") no-repeat center center/cover;
  color: white;
}

body::before {
  content: "";
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(3px);
}

.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: 260px;
  height: 100vh;
}

.main {
  margin-left: 260px;
  padding: 40px;
  position: relative;
  z-index: 1;
}

h2 {
  margin-bottom: 20px;
}

/* GLASS TABLE */
table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255,255,255,0.10);
  backdrop-filter: blur(18px);
  border-radius: 12px;
  overflow: hidden;
}

th {
  text-align: left;
  padding: 12px;
  background: rgba(255,255,255,0.15);
}

td {
  padding: 12px;
  border-top: 1px solid rgba(255,255,255,0.1);
}

/* SELECT */
select {
  padding: 6px;
  border-radius: 6px;
  background: rgba(0,0,0,0.3);
  color: #fff;
  border: 1px solid rgba(255,255,255,0.2);
}

/* BUTTON */
.btn {
  padding: 6px 10px;
  border-radius: 6px;
  border: none;
  background: #fff;
  color: #000;
  font-weight: 600;
  cursor: pointer;
}

/* RESUME */
.resume {
  color: #60a5fa;
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main">

<h2>Applications</h2>

<table>
<tr>
  <th>User</th>
  <th>Job</th>
  <th>Status</th>
  <th>Resume</th>
  <th>Action</th>
</tr>

<?php foreach($app as $a): ?>
<tr>
  <td><?= htmlspecialchars($a['full_name']) ?></td>
  <td><?= htmlspecialchars($a['title']) ?></td>
  <td><?= htmlspecialchars($a['status']) ?></td>

  <td>
    <?php if (!empty($a['resume_path'])): ?>
      <a class="resume" target="_blank"
         href="../uploads/<?= htmlspecialchars($a['resume_path']) ?>">
         View Resume
      </a>
    <?php else: ?>
      No file
    <?php endif; ?>
  </td>

  <td>
    <form method="POST">
      <input type="hidden" name="id" value="<?= $a['id'] ?>">

      <select name="status">
        <option value="pending">pending</option>
        <option value="reviewed">reviewed</option>
        <option value="shortlisted">shortlisted</option>
        <option value="rejected">rejected</option>
        <option value="hired">hired</option>
      </select>

      <button class="btn">Update</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>

</table>

</div>

</body>
</html>