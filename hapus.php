<?php
// 1. Panggil koneksi database & session
require_once 'config/database.php';

// 2. Proteksi halaman: Wajib login
require_once 'includes/auth.php';

// Eksekusi HANYA jika dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);

    if ($id) {
        // Hapus data secara aman menggunakan Prepared Statement
        $stmt = mysqli_prepare($koneksi, "DELETE FROM barang WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash_msg'] = $lang['msg_success_del'];
        }
        mysqli_stmt_close($stmt);
    }
}

// Selalu kembalikan ke halaman utama setelah proses selesai
header("Location: index.php");
exit();