<?php
require_once 'includes/auth.php';
require_once 'includes/header.php';

// Ambil statistik ringkasan
$q_stats = "SELECT 
                COUNT(*) as total_jenis, 
                COALESCE(SUM(jumlah),0) as total_stok, 
                COALESCE(SUM(jumlah * harga),0) as total_aset 
            FROM barang";
$res_stats = mysqli_query($koneksi, $q_stats);
$stats = mysqli_fetch_assoc($res_stats);

// Ambil data barang
$query = "SELECT * FROM barang ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!-- Header Section & Tombol Aksi -->
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-800 text-dark m-0 tracking-tight" style="font-weight: 800;"><?= htmlspecialchars($lang['dashboard']); ?></h3>
        <p class="text-muted small m-0 mt-1">Sistem Pemantauan & Manajemen Aset Inventaris Real-time</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="tambah.php" class="btn btn-primary-custom px-4 py-2.5 shadow-sm rounded-3 fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> <?= htmlspecialchars($lang['add_item']); ?>
        </a>
    </div>
</div>

<!-- Cards Ringkasan Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat border-0 shadow-sm rounded-4 bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-indigo bg-opacity-10 text-primary rounded-4 me-3">
                    <i class="bi bi-boxes fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold d-block">Total Jenis Barang</span>
                    <h3 class="fw-bold m-0 text-dark"><?= number_format($stats['total_jenis'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat border-0 shadow-sm rounded-4 bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 me-3">
                    <i class="bi bi-layers-half fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold d-block">Total Unit Stok</span>
                    <h3 class="fw-bold m-0 text-dark"><?= number_format($stats['total_stok'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat border-0 shadow-sm rounded-4 bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-4 me-3">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold d-block">Total Nilai Aset</span>
                    <h3 class="fw-bold m-0 text-dark">Rp <?= number_format($stats['total_aset'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Notifikasi -->
<?php if (isset($_SESSION['flash_msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><strong>Sukses!</strong> <?= htmlspecialchars($_SESSION['flash_msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_msg']); ?>
<?php endif; ?>

<!-- Main Table Container -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <!-- Filter & Live Search Bar -->
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control bg-light border-start-0" placeholder="Cari kode, nama, atau kategori barang...">
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="inventoryTable">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-secondary text-uppercase fs-7 fw-bold" style="font-size: 0.75rem;">No</th>
                        <th class="py-3 text-secondary text-uppercase fs-7 fw-bold" style="font-size: 0.75rem;"><?= htmlspecialchars($lang['code']); ?></th>
                        <th class="py-3 text-secondary text-uppercase fs-7 fw-bold" style="font-size: 0.75rem;"><?= htmlspecialchars($lang['name']); ?></th>
                        <th class="py-3 text-secondary text-uppercase fs-7 fw-bold" style="font-size: 0.75rem;"><?= htmlspecialchars($lang['category']); ?></th>
                        <th class="py-3 text-secondary text-uppercase fs-7 fw-bold" style="font-size: 0.75rem;"><?= htmlspecialchars($lang['quantity']); ?></th>
                        <th class="py-3 text-secondary text-uppercase fs-7 fw-bold" style="font-size: 0.75rem;"><?= htmlspecialchars($lang['price']); ?></th>
                        <th class="pe-4 py-3 text-center text-secondary text-uppercase fs-7 fw-bold" style="font-size: 0.75rem;"><?= htmlspecialchars($lang['actions']); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $no++; ?></td>
                                <td>
                                    <span class="badge bg-slate-100 text-dark border font-monospace px-2.5 py-1.5 rounded-2">
                                        <?= htmlspecialchars($row['kode_barang']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($row['nama_barang']); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1.5 rounded-pill fw-semibold">
                                        <?= htmlspecialchars($row['kategori']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['jumlah'] < 10): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2.5 py-1 rounded-2">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i><?= number_format($row['jumlah'], 0, ',', '.'); ?> (Stok Menipis)
                                        </span>
                                    <?php else: ?>
                                        <span class="fw-semibold text-dark"><?= number_format($row['jumlah'], 0, ',', '.'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold text-slate-700">
                                    Rp <?= number_format($row['harga'], 2, ',', '.'); ?>
                                </td>
                                <td class="pe-4 text-center">
                                    <div class="btn-group">
                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-light btn-sm text-warning fw-semibold border me-1">
                                            <i class="bi bi-pencil-square"></i> <?= htmlspecialchars($lang['edit']); ?>
                                        </a>
                                        <button class="btn btn-light btn-sm text-danger fw-semibold border" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id']; ?>">
                                            <i class="bi bi-trash"></i> <?= htmlspecialchars($lang['delete']); ?>
                                        </button>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="modalHapus<?= $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($lang['confirm_delete']); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body py-3 text-start">
                                                    <p class="m-0">Apakah Anda yakin ingin menghapus barang <strong><?= htmlspecialchars($row['nama_barang']); ?></strong> (<?= htmlspecialchars($row['kode_barang']); ?>)?</p>
                                                    <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal"><?= htmlspecialchars($lang['cancel']); ?></button>
                                                    <form action="hapus.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                        <button type="submit" class="btn btn-danger px-4 rounded-3"><?= htmlspecialchars($lang['delete']); ?></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <p class="m-0"><?= htmlspecialchars($lang['empty_data']); ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script Live Search Klien -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#inventoryTable tbody tr');
    
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>