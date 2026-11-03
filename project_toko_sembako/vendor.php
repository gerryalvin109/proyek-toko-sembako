<?php
require_once 'koneksi.php';

$pesan = ""; $error = "";

if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id     = (int)$_POST['idvendor'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_vendor']);
    $bh     = mysqli_real_escape_string($conn, $_POST['badan_hukum']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    if (mysqli_query($conn, "INSERT INTO vendor (idvendor, nama_vendor, badan_hukum, status) VALUES ($id, '$nama', '$bh', '$status')")) $pesan = "Vendor berhasil ditambahkan!";
    else $error = "Gagal menambah vendor: " . mysqli_error($conn);
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id     = (int)$_POST['idvendor'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_vendor']);
    $bh     = mysqli_real_escape_string($conn, $_POST['badan_hukum']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    if (mysqli_query($conn, "UPDATE vendor SET nama_vendor='$nama', badan_hukum='$bh', status='$status' WHERE idvendor=$id")) $pesan = "Vendor berhasil diperbarui!";
    else $error = "Gagal mengubah vendor: " . mysqli_error($conn);
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM vendor WHERE idvendor=$id")) $pesan = "Vendor berhasil dihapus!";
    else $error = "Vendor tidak dapat dihapus karena sudah memiliki riwayat pengadaan.";
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'all';
$nama_view = ($mode == 'aktif') ? 'v_vendor_aktif' : 'v_vendor_all';

$q_max = mysqli_query($conn, "SELECT COALESCE(MAX(idvendor), 0) + 1 AS next_id FROM vendor");
$next_id = mysqli_fetch_assoc($q_max)['next_id'];

$data_vendor = [];
$res = mysqli_query($conn, "SELECT * FROM $nama_view ORDER BY id_vendor ASC");
while ($row = mysqli_fetch_assoc($res)) { $data_vendor[] = $row; }

render_header("Master Vendor");
render_sidebar("vendor");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan): ?><div class="alert alert-success alert-dismissible fade show"><?= $pesan ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Master Vendor</h4>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <a href="vendor.php?mode=all" class="btn <?= $mode == 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua Data</a>
                <a href="vendor.php?mode=aktif" class="btn <?= $mode == 'aktif' ? 'btn-success' : 'btn-outline-success' ?>">Data Aktif</a>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Vendor</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID Vendor</th>
                        <th>Nama Vendor</th>
                        <th>Badan Hukum</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_vendor as $row): ?>
                    <tr>
                        <td><?= $row['id_vendor'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nama_vendor']) ?></td>
                        <td><?= $row['ket_badan_hukum'] ?> (<?= $row['badan_hukum'] ?>)</td>
                        <td><span class="badge <?= $row['ket_status'] == 'Aktif' ? 'bg-success' : 'bg-danger' ?>"><?= $row['ket_status'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_vendor'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="vendor.php?mode=<?= $mode ?>&hapus=<?= $row['id_vendor'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus vendor ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT VENDOR (DI LUAR TABLE) -->
<?php foreach ($data_vendor as $row): ?>
<div class="modal fade" id="modalEdit<?= $row['id_vendor'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Edit Vendor #<?= $row['id_vendor'] ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="idvendor" value="<?= $row['id_vendor'] ?>">
                <div class="mb-3"><label class="form-label">Nama Vendor</label><input type="text" name="nama_vendor" class="form-control" value="<?= htmlspecialchars($row['nama_vendor']) ?>" required></div>
                <div class="mb-3">
                    <label class="form-label">Badan Hukum</label>
                    <select name="badan_hukum" class="form-select">
                        <option value="Y" <?= $row['badan_hukum']=='Y'?'selected':'' ?>>Y - Berbadan Hukum</option>
                        <option value="T" <?= $row['badan_hukum']=='T'?'selected':'' ?>>T - Tidak Berbadan Hukum</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= $row['status']=='1'?'selected':'' ?>>1 - Aktif</option>
                        <option value="0" <?= $row['status']=='0'?'selected':'' ?>>0 - Non-Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<!-- MODAL TAMBAH VENDOR -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Vendor Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="tambah">
                <div class="mb-3"><label class="form-label">ID Vendor</label><input type="number" name="idvendor" class="form-control" value="<?= $next_id ?>" required></div>
                <div class="mb-3"><label class="form-label">Nama Vendor</label><input type="text" name="nama_vendor" class="form-control" required></div>
                <div class="mb-3">
                    <label class="form-label">Badan Hukum</label>
                    <select name="badan_hukum" class="form-select">
                        <option value="Y">Y - Berbadan Hukum</option>
                        <option value="T">T - Tidak Berbadan Hukum</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">1 - Aktif</option>
                        <option value="0">0 - Non-Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php render_footer(); ?>