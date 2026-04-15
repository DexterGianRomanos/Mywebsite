<?php
require_once "db.php";

/* =========================
   BASIC CHECKS
========================= */

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/* =========================
   AUTO LOGIN PROTECTION
   (SESSION TIMEOUT)
========================= */
function checkSessionTimeout() {
    $timeout = 1800; // 30 minutes

    if (isset($_SESSION['login_time'])) {
        if (time() - $_SESSION['login_time'] > $timeout) {
            session_unset();
            session_destroy();
            header("Location: /jobportal/login.php");
            exit();
        }
    }
}

/* =========================
   REQUIRE LOGIN (USER/ADMIN)
========================= */
function requireLogin() {

    checkSessionTimeout();

    if (!isLoggedIn()) {
        header("Location: /jobportal/login.php");
        exit();
    }
}

/* =========================
   REQUIRE ADMIN ONLY
========================= */
function requireAdmin() {

    checkSessionTimeout();

    if (!isLoggedIn()) {
        header("Location: /jobportal/login.php");
        exit();
    }

    if (!isAdmin()) {
        header("Location: /jobportal/index.php");
        exit();
    }
}

/* =========================
   SECURE SESSION START CHECK
========================= */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>