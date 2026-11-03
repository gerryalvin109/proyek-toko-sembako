<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "uts_pbd";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

function render_header($title) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistem PBD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #1e293b; color: white; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 12px 20px; display: block; border-radius: 6px; margin-bottom: 4px; }
        .sidebar a:hover, .sidebar a.active { background-color: #3b82f6; color: white; font-weight: 500; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
<?php
}

function render_sidebar($active_page) {
?>
        <div class="col-md-3 col-lg-2 sidebar p-3">
            <h5 class="fw-bold text-white mb-4 px-2"><i class="bi bi-shop me-2"></i>Toko Sembako</h5>
            <div class="small text-uppercase text-secondary px-2 mb-2">Menu Utama</div>
            <a href="index.php" class="<?= $active_page == 'dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i>Simulasi Margin & Harga</a>
            <a href="transaksi_stok.php" class="<?= $active_page == 'stok' ? 'active' : '' ?>"><i class="bi bi-boxes me-2"></i>Transaksi & Kartu Stok</a>
            <div class="small text-uppercase text-secondary px-2 mt-4 mb-2">6 Data Master</div>
            <a href="barang.php" class="<?= $active_page == 'barang' ? 'active' : '' ?>"><i class="bi bi-box-seam me-2"></i>1. Master Barang</a>
            <a href="satuan.php" class="<?= $active_page == 'satuan' ? 'active' : '' ?>"><i class="bi bi-tags me-2"></i>2. Master Satuan</a>
            <a href="vendor.php" class="<?= $active_page == 'vendor' ? 'active' : '' ?>"><i class="bi bi-truck me-2"></i>3. Master Vendor</a>
            <a href="margin.php" class="<?= $active_page == 'margin' ? 'active' : '' ?>"><i class="bi bi-percent me-2"></i>4. Margin Penjualan</a>
            <a href="user.php" class="<?= $active_page == 'user' ? 'active' : '' ?>"><i class="bi bi-people me-2"></i>5. Master User</a>
            <a href="role.php" class="<?= $active_page == 'role' ? 'active' : '' ?>"><i class="bi bi-shield-lock me-2"></i>6. Master Role</a>
        </div>
<?php
}

function render_footer() {
?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>