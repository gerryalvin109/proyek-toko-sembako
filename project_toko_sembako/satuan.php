<?php
require_once 'koneksi.php';

$pesan = ""; $error = "";

if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id     = (int)$_POST['idsatuan'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_satuan']);
    $status = (int)$_POST['status'];
    if (mysqli_query($conn, "INSERT INTO satuan (idsatuan, nama_satuan, status) VALUES ($id, '$nama', $status)")) $pesan = "Satuan berhasil ditambahkan!";
    else $error = "Gagal menambah satuan: " . mysqli_error($conn);
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id     = (int)$_POST['idsatuan'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_satuan']);
    $status = (int)$_POST['status'];
    if (mysqli_query($conn, "UPDATE satuan SET nama_satuan='$nama', status=$status WHERE idsatuan=$id")) $pesan = "Satuan berhasil diperbarui!";
    else $error = "Gagal mengubah satuan: " . mysqli_error($conn);
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM satuan WHERE idsatuan=$id")) $pesan = "Satuan berhasil dihapus!";
    else $error = "Satuan tidak dapat dihapus karena sedang digunakan pada tabel Barang.";
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'all';
$nama_view = ($mode == 'aktif') ? 'v_satuan_aktif' : 'v_satuan_all';

$q_max = mysqli_query($conn, "SELECT COALESCE(MAX(idsatuan), 0) + 1 AS next_id FROM satuan");
$next_id = mysqli_fetch_assoc($q_max)['next_id'];

$data_satuan = [];
$res = mysqli_query($conn, "SELECT * FROM $nama_view ORDER BY id_satuan ASC");
while ($row = mysqli_fetch_assoc($res)) { $data_satuan[] = $row; }

render_header("Master Satuan");
render_sidebar("satuan");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan): ?><div class="alert alert-success alert-dismissible fade show"><?= $pesan ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Master Satuan</h4>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <a href="satuan.php?mode=all" class="btn <?= $mode == 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua Data</a>
                <a href="satuan.php?mode=aktif" class="btn <?= $mode == 'aktif' ? 'btn-success' : 'btn-outline-success' ?>">Data Aktif</a>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Satuan</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID Satuan</th>
                        <th>Nama Satuan</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_satuan as $row): ?>
                    <tr>
                        <td><?= $row['id_satuan'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nama_satuan']) ?></td>
                        <td><span class="badge <?= $row['ket_status'] == 'Aktif' ? 'bg-success' : 'bg-danger' ?>"><?= $row['ket_status'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_satuan'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="satuan.php?mode=<?= $mode ?>&hapus=<?= $row['id_satuan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus satuan ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT SATUAN (DI LUAR TABLE) -->
<?php foreach ($data_satuan as $row): ?>
<div class="modal fade" id="modalEdit<?= $row['id_satuan'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Satuan #<?= $row['id_satuan'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="idsatuan" value="<?= $row['id_satuan'] ?>">
                <div class="mb-3"><label class="form-label">Nama Satuan</label><input type="text" name="nama_satuan" class="form-control" value="<?= htmlspecialchars($row['nama_satuan']) ?>" required></div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?= $row['status']==1?'selected':'' ?>>1 - Aktif</option>
                        <option value="0" <?= $row['status']==0?'selected':'' ?>>0 - Non-Aktif</option>
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

<!-- MODAL TAMBAH SATUAN -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Satuan Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="tambah">
                <div class="mb-3"><label class="form-label">ID Satuan</label><input type="number" name="idsatuan" class="form-control" value="<?= $next_id ?>" required></div>
                <div class="mb-3"><label class="form-label">Nama Satuan</label><input type="text" name="nama_satuan" class="form-control" required></div>
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