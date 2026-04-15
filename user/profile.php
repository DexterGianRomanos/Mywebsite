<?php
include "../includes/header.php"; 
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/functions.php";

requireLogin();

$user_id = $_SESSION['user_id'];

/* =========================
   GET USER SAFELY
========================= */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

/* ❗ FIX: if user not found */
if (!$user) {
    die("User not found.");
}

$success = [];
$errors = [];

/* =========================
   UPDATE PROFILE
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['csrf'] ?? '')) {
        die("Invalid CSRF token");
    }

    $name    = sanitize($_POST['full_name'] ?? '');
    $phone   = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');

    $profile_pic = $user['profile_pic'] ?? 'default.png';

    /* =========================
       UPLOAD IMAGE
    ========================= */
    if (!empty($_FILES['profile_pic']['name'])) {

        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($_FILES['profile_pic']['type'], $allowed)) {
            $errors[] = "Only JPG and PNG images are allowed.";
        } else {

            $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
            $newName = "profile_" . $user_id . "_" . time() . "." . $ext;

            $uploadPath = "../uploads/" . $newName;

            if (!is_dir("../uploads")) {
                mkdir("../uploads", 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                $profile_pic = $newName;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    /* =========================
       UPDATE DATABASE
    ========================= */
    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE users 
            SET full_name=?, phone=?, address=?, profile_pic=? 
            WHERE id=?
        ");

        $stmt->execute([
            $name,
            $phone,
            $address,
            $profile_pic,
            $user_id
        ]);

        $success[] = "Profile updated successfully!";

        /* reload user */
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Jost', sans-serif;
}

/* BACKGROUND */
body {
    min-height: 100vh;
    background: url("../assets/images/bg.jpg") no-repeat center center/cover;
    color: #fff;
}

/* OVERLAY */
body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(2px);
}

/* CENTER */
.container {
    margin-left: 260px;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
    position: relative;
}

/* CARD */
.card {
    width: 100%;
    max-width: 600px;
    padding: 30px;
    border-radius: 18px;

    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(255,255,255,0.2);
}

/* PROFILE IMG */
.profile-img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
}

/* INPUT */
input, textarea {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.08);
    color: #fff;
}

/* BUTTON */
button {
    background: #fff;
    color: #000;
    padding: 12px 18px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
}

/* ALERTS */
.success {
    background: rgba(34,197,94,0.15);
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
}

.error {
    background: rgba(239,68,68,0.15);
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="container">

<div class="card">

    <h2>My Profile</h2>

    <?php foreach($success as $s): ?>
        <div class="success"><?= $s ?></div>
    <?php endforeach; ?>

    <?php foreach($errors as $e): ?>
        <div class="error"><?= $e ?></div>
    <?php endforeach; ?>

    <img class="profile-img"
         src="../uploads/<?= htmlspecialchars($user['profile_pic'] ?? 'default.png') ?>"
         onerror="this.src='../uploads/default.png'">

    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

        <label>Full Name</label>
        <input type="text" name="full_name"
               value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">

        <label>Phone</label>
        <input type="text" name="phone"
               value="<?= htmlspecialchars($user['phone'] ?? '') ?>">

        <label>Address</label>
        <textarea name="address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>

        <label>Profile Picture</label>
        <input type="file" name="profile_pic">

        <button type="submit">Update Profile</button>

    </form>

</div>

</div>

</body>
</html>