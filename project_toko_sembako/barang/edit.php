<?php
$page_title  = "Edit Barang";
$active_menu = "barang";
require_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis    = mysqli_real_escape_string($conn, $_POST['jenis']);
    $idsatuan = (int)$_POST['idsatuan'];
    $harga    = (int)$_POST['harga'];
    $status   = (int)$_POST['status'];

    mysqli_query($conn, "UPDATE barang SET jenis='$jenis', nama='$nama', idsatuan=$idsatuan, status=$status, harga=$harga WHERE idbarang=$id");
    header("Location: index.php");
    exit;
}

$data   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE idbarang=$id"));
$satuan = mysqli_query($conn, "SELECT * FROM v_satuan_all");
?>
<h4 class="fw-bold mb-4">Edit Barang</h4>
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis</label>
                <input type="text" name="jenis" maxlength="1" class="form-control" value="<?= htmlspecialchars($data['jenis'] ?? 'B') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Satuan</label>
                <select name="idsatuan" class="form-select" required>
                    <?php while ($s = mysqli_fetch_assoc($satuan)): ?>
                        <option value="<?= $s['id_satuan'] ?>" <?= ($data['idsatuan'] ?? 0) == $s['id_satuan'] ? 'selected' : '' ?>><?= htmlspecialchars($s['nama_satuan']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga Pokok</label>
                <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?? 0 ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" <?= ($data['status'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($data['status'] ?? 1) == 0 ? 'selected' : '' ?>>Non-Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
