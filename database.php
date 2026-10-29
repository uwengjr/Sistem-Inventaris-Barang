<?php
// 1. Jalankan session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Pengaturan Koneksi Database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Default XAMPP biasanya kosong
$db_name = 'db_inventaris';

// 3. Membuat Koneksi ke MySQL/MariaDB menggunakan MySQLi
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// 4. Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// 5. Pengaturan Bahasa Default (Indonesia)
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id';
}

// 6. Mengubah bahasa jika pengguna mengeklik tombol pemilih bahasa di URL
if (isset($_GET['lang']) && in_array($_GET['lang'], ['id', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// 7. Muat file kamus bahasa yang sesuai
$lang_code = $_SESSION['lang'];
$lang_file = __DIR__ . "/../lang/{$lang_code}.php";

if (file_exists($lang_file)) {
    $lang = require $lang_file;
} else {
    $lang = require __DIR__ . "/../lang/id.php";
}