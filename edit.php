<?php
// 1. Panggil koneksi database & session
require_once 'config/database.php';

// 2. Proteksi halaman: Wajib login
require_once 'includes/auth.php';

// Ambil ID dari URL parameter & pastikan tipenya integer
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit();
}

$error_message = '';

// Ambil data lama dari database berdasarkan ID
$stmt = mysqli_prepare($koneksi, "SELECT * FROM barang WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$barang = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Jika ID tidak ditemukan di DB, kembalikan ke index.php
if (!$barang) {
    header("Location: index.php");
    exit();
}

// Proses saat tombol Perbarui (submit) diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi Data Input
    $kode_barang = strtoupper(trim($_POST['kode_barang'] ?? ''));
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $kategori    = trim($_POST['kategori'] ?? '');
    
    $jumlah      = filter_var($_POST['jumlah'] ?? 0, FILTER_VALIDATE_INT);
    $harga       = filter_var($_POST['harga'] ?? 0, FILTER_VALIDATE_FLOAT);

    if (empty($kode_barang) || empty($nama_barang) || empty($kategori) || $jumlah === false || $harga === false) {
        $error_message = $lang['msg_empty_fields'];
    } else {
        // Cek apakah kode barang bentrok dengan data barang LAIN
        $stmt_check = mysqli_prepare($koneksi, "SELECT id FROM barang WHERE kode_barang = ? AND id != ?");
        mysqli_stmt_bind_param($stmt_check, "si", $kode_barang, $id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error_message = $lang['msg_code_exists'];
        } else {
            // Update data dengan Prepared Statement
            $stmt_update = mysqli_prepare($koneksi, "UPDATE barang SET kode_barang = ?, nama_barang = ?, kategori = ?, jumlah = ?, harga = ? WHERE id = ?");
            // Parameter tipe data: s = string, i = integer, d = double/float
            mysqli_stmt_bind_param($stmt_update, "sssidi", $kode_barang, $nama_barang, $kategori, $jumlah, $harga, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['flash_msg'] = $lang['msg_success_edit'];
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Terjadi kesalahan saat memperbarui data.";
            }
            mysqli_stmt_close($stmt_update);
        }
        mysqli_stmt_close($stmt_check);
    }
} else {
    // Isi field form dengan data lama dari DB saat pertama kali dibuka
    $_POST = $barang;
}

require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="m-0 fw-bold"><?= htmlspecialchars($lang['edit_item']); ?></h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger py-2"><?= htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form action="edit.php?id=<?= $id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="kode_barang" class="form-label"><?= htmlspecialchars($lang['code']); ?></label>
                        <input type="text" name="kode_barang" id="kode_barang" class="form-control" value="<?= htmlspecialchars($_POST['kode_barang'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label"><?= htmlspecialchars($lang['name']); ?></label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= htmlspecialchars($_POST['nama_barang'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label"><?= htmlspecialchars($lang['category']); ?></label>
                        <input type="text" name="kategori" id="kategori" class="form-control" value="<?= htmlspecialchars($_POST['kategori'] ?? ''); ?>" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jumlah" class="form-label"><?= htmlspecialchars($lang['quantity']); ?></label>
                            <input type="number" min="0" name="jumlah" id="jumlah" class="form-control" value="<?= htmlspecialchars($_POST['jumlah'] ?? '0'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="harga" class="form-label"><?= htmlspecialchars($lang['price']); ?></label>
                            <input type="number" step="0.01" min="0" name="harga" id="harga" class="form-control" value="<?= htmlspecialchars($_POST['harga'] ?? '0'); ?>" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary"><?= htmlspecialchars($lang['cancel']); ?></a>
                        <button type="submit" class="btn btn-warning fw-bold"><?= htmlspecialchars($lang['update']); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>