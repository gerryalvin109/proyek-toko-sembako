<?php
$page_title  = "Edit Vendor";
$active_menu = "vendor";
require_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_vendor']);
    $bh     = mysqli_real_escape_string($conn, $_POST['badan_hukum']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE vendor SET nama_vendor='$nama', badan_hukum='$bh', status='$status' WHERE idvendor=$id");
    header("Location: index.php");
    exit;
}
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vendor WHERE idvendor=$id"));
?>
<h4 class="fw-bold mb-4">Edit Vendor</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Nama Vendor</label><input type="text" name="nama_vendor" class="form-control" value="<?= htmlspecialchars($data['nama_vendor'] ?? '') ?>" required></div>
            <div class="mb-3"><label class="form-label">Badan Hukum</label><select name="badan_hukum" class="form-select"><option value="Y" <?= ($data['badan_hukum'] ?? '') == 'Y' ? 'selected' : '' ?>>Ya</option><option value="N" <?= ($data['badan_hukum'] ?? '') == 'N' ? 'selected' : '' ?>>Tidak</option></select></div>
            <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="A" <?= ($data['status'] ?? '') == 'A' ? 'selected' : '' ?>>Aktif (A)</option><option value="N" <?= ($data['status'] ?? '') == 'N' ? 'selected' : '' ?>>Non-Aktif (N)</option></select></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
