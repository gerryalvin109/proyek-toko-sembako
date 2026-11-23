<?php
$page_title  = "Tambah Margin";
$active_menu = "margin";
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $persen = (float)$_POST['persen'];
    $iduser = (int)$_SESSION['iduser'];
    mysqli_query($conn, "INSERT INTO margin_penjualan (persen, status, iduser) VALUES ($persen, 0, $iduser)");
    header("Location: index.php");
    exit;
}
?>
<h4 class="fw-bold mb-4">Tambah Margin Penjualan</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Persentase Margin (%)</label><input type="number" step="0.1" name="persen" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
