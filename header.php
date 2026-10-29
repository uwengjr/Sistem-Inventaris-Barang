<?php
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang']); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lang['app_title']); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-body: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
        }
        .navbar-custom {
            background: #0f172a;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.15);
        }
        .brand-icon {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            width: 38px;
            height: 38px;
            border-radius: 10px;
        }
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            color: #fff;
            transform: translateY(-1px);
        }
        .card-stat {
            border: 1px solid #e2e8f0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(0,0,0,0.08) !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top mb-4 py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-3 fw-bold fs-5" href="index.php">
      <div class="brand-icon d-flex align-items-center justify-content-center text-white shadow-sm">
        <i class="bi bi-box-seam-fill fs-5"></i>
      </div>
      <span class="tracking-tight"><?= htmlspecialchars($lang['app_title']); ?></span>
    </a>
    
    <?php if (isset($_SESSION['user_id'])): ?>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto ms-lg-3">
        <li class="nav-item">
          <a class="nav-link text-white fw-medium px-3 py-2 rounded-3 bg-white bg-opacity-10" href="index.php">
             <i class="bi bi-grid-1x2-fill me-1"></i> <?= htmlspecialchars($lang['dashboard']); ?>
          </a>
        </li>
      </ul>
      
      <div class="d-flex align-items-center gap-3">
        <!-- Switcher Bahasa Toggle Pill -->
        <div class="bg-dark p-1 rounded-pill d-flex gap-1 border border-secondary border-opacity-25">
          <a href="?lang=id" class="btn btn-sm rounded-pill px-3 py-1 text-decoration-none <?= $_SESSION['lang'] === 'id' ? 'btn-primary-custom fw-bold' : 'text-white-50'; ?>">ID</a>
          <a href="?lang=en" class="btn btn-sm rounded-pill px-3 py-1 text-decoration-none <?= $_SESSION['lang'] === 'en' ? 'btn-primary-custom fw-bold' : 'text-white-50'; ?>">EN</a>
        </div>
        
        <!-- User Avatar -->
        <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-1.5 rounded-pill border border-white border-opacity-10 text-white">
          <div class="bg-primary bg-opacity-25 text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
            <?= strtoupper(substr($_SESSION['nama_lengkap'] ?? 'A', 0, 1)); ?>
          </div>
          <span class="small fw-semibold"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></span>
        </div>
        
        <a href="logout.php" class="btn btn-outline-danger btn-sm px-3 fw-semibold rounded-pill">
          <i class="bi bi-box-arrow-right me-1"></i> <?= htmlspecialchars($lang['logout']); ?>
        </a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</nav>

<div class="container pb-5">