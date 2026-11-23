<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
cek_login();

$page_title  = $page_title ?? 'Toko Sembako';
$active_menu = $active_menu ?? '';
$role        = strtolower($_SESSION['nama_role'] ?? '');

$is_admin  = ($role === 'admin' || $role === 'super admin');
$is_kasir  = ($role === 'kasir');
$is_gudang = ($role === 'staff gudang' || $role === 'gudang');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Toko Sembako</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar min-vh-100 p-3">
            <h5 class="fw-bold mb-1 px-2"><i class="bi bi-shop me-2"></i>Toko Sembako</h5>
            <div class="small text-secondary px-2 mb-3">
                <?= htmlspecialchars($_SESSION['username']) ?> (<?= htmlspecialchars($_SESSION['nama_role']) ?>)
            </div>

            <div class="small text-uppercase text-secondary px-2 mb-1">Utama</div>
            <?php if ($is_admin || $is_kasir): ?>
                <a href="<?= BASE_URL ?>/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'dashboard' ? 'bg-primary' : '' ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/stok/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'stok' ? 'bg-primary' : '' ?>"><i class="bi bi-boxes me-2"></i>Kartu Stok</a>

            <?php if ($is_admin || $is_gudang): ?>
                <div class="small text-uppercase text-secondary px-2 mt-3 mb-1">Data Master</div>
                <a href="<?= BASE_URL ?>/barang/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'barang' ? 'bg-primary' : '' ?>"><i class="bi bi-box-seam me-2"></i>Barang</a>
                <a href="<?= BASE_URL ?>/satuan/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'satuan' ? 'bg-primary' : '' ?>"><i class="bi bi-tags me-2"></i>Satuan</a>
                <a href="<?= BASE_URL ?>/vendor/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'vendor' ? 'bg-primary' : '' ?>"><i class="bi bi-truck me-2"></i>Vendor</a>
            <?php endif; ?>

            <?php if ($is_admin): ?>
                <a href="<?= BASE_URL ?>/margin/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'margin' ? 'bg-primary' : '' ?>"><i class="bi bi-percent me-2"></i>Margin Penjualan</a>
                <a href="<?= BASE_URL ?>/user/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'user' ? 'bg-primary' : '' ?>"><i class="bi bi-people me-2"></i>User</a>
                <a href="<?= BASE_URL ?>/role/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'role' ? 'bg-primary' : '' ?>"><i class="bi bi-shield-lock me-2"></i>Role</a>
            <?php endif; ?>

            <div class="small text-uppercase text-secondary px-2 mt-3 mb-1">Transaksi</div>
            <?php if ($is_admin || $is_gudang): ?>
                <a href="<?= BASE_URL ?>/pengadaan/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'pengadaan' ? 'bg-primary' : '' ?>"><i class="bi bi-cart-plus me-2"></i>Pengadaan</a>
                <a href="<?= BASE_URL ?>/penerimaan/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'penerimaan' ? 'bg-primary' : '' ?>"><i class="bi bi-box-arrow-in-down me-2"></i>Penerimaan</a>
            <?php endif; ?>

            <?php if ($is_admin || $is_kasir): ?>
                <a href="<?= BASE_URL ?>/penjualan/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'penjualan' ? 'bg-primary' : '' ?>"><i class="bi bi-receipt me-2"></i>Penjualan</a>
            <?php endif; ?>

            <?php if ($is_admin || $is_gudang): ?>
                <a href="<?= BASE_URL ?>/retur/index.php" class="nav-link text-white rounded px-2 py-1 mb-1 <?= $active_menu == 'retur' ? 'bg-primary' : '' ?>"><i class="bi bi-arrow-return-left me-2"></i>Retur</a>
            <?php endif; ?>

            <hr class="border-secondary">
            <a href="<?= BASE_URL ?>/logout.php" class="nav-link text-danger rounded px-2 py-1"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
        </nav>

        <main class="col-md-9 col-lg-10 p-4">