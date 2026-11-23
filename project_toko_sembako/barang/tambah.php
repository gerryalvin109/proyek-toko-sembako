<?php
$page_title  = "Tambah Barang";
$active_menu = "barang";
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis    = mysqli_real_escape_string($conn, $_POST['jenis']);
    $idsatuan = (int)$_POST['idsatuan'];
    $harga    = (int)$_POST['harga'];
    $status   = (int)$_POST['status'];

    mysqli_query($conn, "INSERT INTO barang (jenis, nama, idsatuan, status, harga) VALUES ('$jenis', '$nama', $idsatuan, $status, $harga)");
    header("Location: index.php");
    exit;
}

$satuan = mysqli_query($conn, "SELECT * FROM v_satuan_aktif");
?>
<h4 class="fw-bold mb-4">Tambah Barang Baru</h4>
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis (1 Karakter, misal: M/B)</label>
                <input type="text" name="jenis" maxlength="1" class="form-control" value="B" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Satuan</label>
                <select name="idsatuan" class="form-select" required>
                    <?php while ($s = mysqli_fetch_assoc($satuan)): ?>
                        <option value="<?= $s['id_satuan'] ?>"><?= htmlspecialchars($s['nama_satuan']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga Pokok</label>
                <input type="number" name="harga" class="form-control" min="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1">Aktif</option>
                    <option value="0">Non-Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
