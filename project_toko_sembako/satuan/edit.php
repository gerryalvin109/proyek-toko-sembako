<?php
$page_title  = "Edit Satuan";
$active_menu = "satuan";
require_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_satuan']);
    $status = (int)$_POST['status'];
    mysqli_query($conn, "UPDATE satuan SET nama_satuan='$nama', status=$status WHERE idsatuan=$id");
    header("Location: index.php");
    exit;
}
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM satuan WHERE idsatuan=$id"));
?>
<h4 class="fw-bold mb-4">Edit Satuan</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Nama Satuan</label><input type="text" name="nama_satuan" class="form-control" value="<?= htmlspecialchars($data['nama_satuan'] ?? '') ?>" required></div>
            <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" <?= ($data['status'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option><option value="0" <?= ($data['status'] ?? 1) == 0 ? 'selected' : '' ?>>Non-Aktif</option></select></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
