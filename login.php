<?php
require_once 'config/database.php';

// Jika user sudah terlanjur login, lempar langsung ke halaman utama
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi input pengguna untuk menghilangkan spasi tak disengaja
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = $lang['msg_empty_fields'];
    } else {
        // Prepared Statement untuk mencegah SQL Injection
        $stmt = mysqli_prepare($koneksi, "SELECT id, username, password, nama_lengkap FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verifikasi password yang sudah di-hash
            if (password_verify($password, $row['password'])) {
                // Regenerasi ID Session untuk cegah Session Fixation Attack
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];

                header("Location: index.php");
                exit();
            }
        }
        
        $error_message = $lang['msg_invalid_login'];
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang']); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lang['login_title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary bg-gradient min-vh-100 d-flex align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title fw-bold m-0"><?= htmlspecialchars($lang['login_title']); ?></h4>
                        <!-- Tombol Pilihan Bahasa -->
                        <div class="btn-group btn-group-sm">
                            <a href="?lang=id" class="btn btn-outline-secondary <?= $_SESSION['lang'] === 'id' ? 'active' : ''; ?>">ID</a>
                            <a href="?lang=en" class="btn btn-outline-secondary <?= $_SESSION['lang'] === 'en' ? 'active' : ''; ?>">EN</a>
                        </div>
                    </div>
                    <hr>

                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label"><?= htmlspecialchars($lang['username']); ?></label>
                            <input type="text" name="username" id="username" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><?= htmlspecialchars($lang['password']); ?></label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><?= htmlspecialchars($lang['login_btn']); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>