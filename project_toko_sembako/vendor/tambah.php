<?php
$page_title  = "Tambah Vendor";
$active_menu = "vendor";
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_vendor']);
    $bh     = mysqli_real_escape_string($conn, $_POST['badan_hukum']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES ('$nama', '$bh', '$status')");
    header("Location: index.php");
    exit;
}
?>
<h4 class="fw-bold mb-4">Tambah Vendor</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Nama Vendor</label><input type="text" name="nama_vendor" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Badan Hukum (Y/N)</label><select name="badan_hukum" class="form-select"><option value="Y">Ya (PT/CV)</option><option value="N">Tidak</option></select></div>
            <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="A">Aktif (A)</option><option value="N">Non-Aktif (N)</option></select></div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
