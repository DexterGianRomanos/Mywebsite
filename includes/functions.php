<?php

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf($token) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function uploadResume($file) {
    if ($file['type'] !== 'application/pdf') return false;

    $name = bin2hex(random_bytes(16)) . ".pdf";
    $path = "uploads/" . $name;

    if (!is_dir("uploads")) mkdir("uploads");

    move_uploaded_file($file['tmp_name'], $path);
    return $path;
}
?>