</div> <!-- Akhir Pembungkus Container Utama -->

<footer class="mt-auto py-4 bg-white border-top">
    <div class="container text-center text-md-between d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
        <div class="text-muted small">
            &copy; <?= date('Y'); ?> <strong><?= htmlspecialchars($lang['app_title']); ?></strong>. Hak Cipta Dilindungi Undang-Undang.
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 small">
                <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Sistem Aktif (PHP Native + MySQLi)
            </span>
        </div>
    </div>
</footer>

<!-- JS Bootstrap 5 Bundle via CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>