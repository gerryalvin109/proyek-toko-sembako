<?php
$page_title  = "Tambah Satuan";
$active_menu = "satuan";
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_satuan']);
    $status = (int)$_POST['status'];
    mysqli_query($conn, "INSERT INTO satuan (nama_satuan, status) VALUES ('$nama', $status)");
    header("Location: index.php");
    exit;
}
?>
<h4 class="fw-bold mb-4">Tambah Satuan</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Nama Satuan</label><input type="text" name="nama_satuan" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Aktif</option><option value="0">Non-Aktif</option></select></div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
