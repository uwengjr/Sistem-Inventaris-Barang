<?php
// 1. Panggil koneksi database & session terlebih dahulu
require_once 'config/database.php';

// 2. Proteksi halaman: Wajib login
require_once 'includes/auth.php';

$error_message = '';

// Proses saat tombol simpan (submit) diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi Data Input
    $kode_barang = strtoupper(trim($_POST['kode_barang'] ?? ''));
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $kategori    = trim($_POST['kategori'] ?? '');
    
    // Validasi tipe data numerik
    $jumlah      = filter_var($_POST['jumlah'] ?? 0, FILTER_VALIDATE_INT);
    $harga       = filter_var($_POST['harga'] ?? 0, FILTER_VALIDATE_FLOAT);

    // Validasi Kelengkapan Data
    if (empty($kode_barang) || empty($nama_barang) || empty($kategori) || $jumlah === false || $harga === false) {
        $error_message = $lang['msg_empty_fields'];
    } else {
        // Cek Keunikan Kode Barang
        $stmt_check = mysqli_prepare($koneksi, "SELECT id FROM barang WHERE kode_barang = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $kode_barang);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error_message = $lang['msg_code_exists'];
        } else {
            // Simpan Data Baru dengan Prepared Statement
            $stmt = mysqli_prepare($koneksi, "INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga) VALUES (?, ?, ?, ?, ?)");
            // Parameter tipe data: s = string, i = integer, d = double/float
            mysqli_stmt_bind_param($stmt, "sssid", $kode_barang, $nama_barang, $kategori, $jumlah, $harga);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['flash_msg'] = $lang['msg_success_add'];
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Terjadi kesalahan sistem saat menyimpan data.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($stmt_check);
    }
}

// Memuat tampilan header setelah logika PHP selesai
require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="m-0 fw-bold"><?= htmlspecialchars($lang['add_item']); ?></h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger py-2"><?= htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form action="tambah.php" method="POST">
                    <div class="mb-3">
                        <label for="kode_barang" class="form-label"><?= htmlspecialchars($lang['code']); ?></label>
                        <input type="text" name="kode_barang" id="kode_barang" class="form-control" placeholder="Contoh: BRG-006" value="<?= htmlspecialchars($_POST['kode_barang'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label"><?= htmlspecialchars($lang['name']); ?></label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= htmlspecialchars($_POST['nama_barang'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label"><?= htmlspecialchars($lang['category']); ?></label>
                        <input type="text" name="kategori" id="kategori" class="form-control" placeholder="Elektronik / Furnitur / ATK" value="<?= htmlspecialchars($_POST['kategori'] ?? ''); ?>" required>
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
                        <button type="submit" class="btn btn-primary fw-bold"><?= htmlspecialchars($lang['save']); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>