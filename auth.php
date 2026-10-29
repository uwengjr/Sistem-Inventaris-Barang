<?php
// Pastikan session sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pengecekan Satpam: Jika belum ada session user_id, tendang ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}