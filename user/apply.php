<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/functions.php";

requireLogin();

$job_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['csrf'])) {
        die("CSRF error");
    }

    $cover = sanitize($_POST['cover']);

    /* =========================
       FILE UPLOAD (ANY FILE TYPE)
    ========================= */
    $resume = null;

    if (!empty($_FILES['resume']['name'])) {

        $uploadDir = "../uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["resume"]["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFile)) {
            $resume = $fileName;
        }
    }

    /* SAVE APPLICATION */
    $stmt = $pdo->prepare("
        INSERT INTO applications(job_id, user_id, cover_letter, resume_path)
        VALUES(?,?,?,?)
    ");

    $stmt->execute([$job_id, $user_id, $cover, $resume]);

    header("Location: my-applications.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Apply Job</title>
<link rel="stylesheet" href="../assets/user.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <div class="card">

        <h2>Apply for Job</h2>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

            <label>Cover Letter</label>
            <textarea name="cover" required></textarea>

            <label>Upload Resume (Any File)</label>
            <input type="file" name="resume">

            <button class="btn">Submit Application</button>

        </form>

    </div>

</div>

</body>
</html>