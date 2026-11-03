<?php
require_once 'koneksi.php';

$pesan = ""; $error = "";

if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id     = (int)$_POST['idmargin'];
    $persen = (float)$_POST['persen'];
    $iduser = (int)$_POST['iduser'];
    $status = (int)$_POST['status'];

    if ($status == 1) {
        mysqli_query($conn, "UPDATE margin_penjualan SET status = 0 WHERE idmargin_penjualan > 0");
    }
    $q = "INSERT INTO margin_penjualan (idmargin_penjualan, persen, status, iduser) VALUES ($id, $persen, $status, $iduser)";
    if (mysqli_query($conn, $q)) $pesan = "Margin penjualan berhasil ditambahkan!";
    else $error = "Gagal menambah margin: " . mysqli_error($conn);
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id     = (int)$_POST['idmargin'];
    $persen = (float)$_POST['persen'];
    $iduser = (int)$_POST['iduser'];
    $status = (int)$_POST['status'];

    if ($status == 1) {
        mysqli_query($conn, "UPDATE margin_penjualan SET status = 0 WHERE idmargin_penjualan > 0");
    }
    $q = "UPDATE margin_penjualan SET persen=$persen, status=$status, iduser=$iduser, updated_at=CURRENT_TIMESTAMP WHERE idmargin_penjualan=$id";
    if (mysqli_query($conn, $q)) $pesan = "Margin penjualan berhasil diperbarui!";
    else $error = "Gagal mengubah margin: " . mysqli_error($conn);
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM margin_penjualan WHERE idmargin_penjualan=$id")) $pesan = "Margin berhasil dihapus!";
    else $error = "Margin tidak dapat dihapus karena sedang digunakan pada data penjualan.";
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'all';
$nama_view = ($mode == 'aktif') ? 'v_margin_aktif' : 'v_margin_all';

$q_max = mysqli_query($conn, "SELECT COALESCE(MAX(idmargin_penjualan), 0) + 1 AS next_id FROM margin_penjualan");
$next_id = mysqli_fetch_assoc($q_max)['next_id'];

$list_user = [];
$q_u = mysqli_query($conn, "SELECT * FROM v_user");
while ($u = mysqli_fetch_assoc($q_u)) { $list_user[] = $u; }

$data_margin = [];
$res = mysqli_query($conn, "SELECT * FROM $nama_view ORDER BY id_margin ASC");
while ($row = mysqli_fetch_assoc($res)) { $data_margin[] = $row; }

render_header("Master Margin Penjualan");
render_sidebar("margin");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan): ?><div class="alert alert-success alert-dismissible fade show"><?= $pesan ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Master Margin Penjualan</h4>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <a href="margin.php?mode=all" class="btn <?= $mode == 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua Data</a>
                <a href="margin.php?mode=aktif" class="btn <?= $mode == 'aktif' ? 'btn-success' : 'btn-outline-success' ?>">Data Aktif</a>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Margin</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Persentase Margin</th>
                        <th>Ditetapkan Oleh</th>
                        <th>Waktu Dibuat</th>
                        <th>Terakhir Diubah</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_margin as $row): ?>
                    <tr>
                        <td><?= $row['id_margin'] ?></td>
                        <td class="fw-bold"><?= $row['tampilan_margin'] ?></td>
                        <td><?= htmlspecialchars($row['ditetapkan_oleh']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td><?= $row['updated_at'] ? $row['updated_at'] : '-' ?></td>
                        <td><span class="badge <?= $row['ket_status'] == 'Aktif' ? 'bg-success' : 'bg-danger' ?>"><?= $row['ket_status'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_margin'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="margin.php?mode=<?= $mode ?>&hapus=<?= $row['id_margin'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus margin ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT MARGIN (DI LUAR TABLE) -->
<?php foreach ($data_margin as $row): ?>
<div class="modal fade" id="modalEdit<?= $row['id_margin'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Edit Margin #<?= $row['id_margin'] ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="idmargin" value="<?= $row['id_margin'] ?>">
                <div class="mb-3"><label class="form-label">Persentase (%)</label><input type="number" step="0.1" name="persen" class="form-control" value="<?= $row['persentase_margin'] ?>" required></div>
                <div class="mb-3">
                    <label class="form-label">Ditetapkan Oleh</label>
                    <select name="iduser" class="form-select">
                        <?php foreach ($list_user as $u): ?>
                            <option value="<?= $u['id_user'] ?>" <?= $row['id_user']==$u['id_user']?'selected':'' ?>><?= $u['username'] ?> (<?= $u['nama_role'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status (Mengaktifkan ini akan menonaktifkan margin lain)</label>
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

<!-- MODAL TAMBAH MARGIN -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Margin Penjualan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="tambah">
                <div class="mb-3"><label class="form-label">ID Margin</label><input type="number" name="idmargin" class="form-control" value="<?= $next_id ?>" required></div>
                <div class="mb-3"><label class="form-label">Persentase (%)</label><input type="number" step="0.1" name="persen" class="form-control" required></div>
                <div class="mb-3">
                    <label class="form-label">Ditetapkan Oleh</label>
                    <select name="iduser" class="form-select">
                        <?php foreach ($list_user as $u): ?>
                            <option value="<?= $u['id_user'] ?>"><?= $u['username'] ?> (<?= $u['nama_role'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="0">0 - Non-Aktif</option>
                        <option value="1">1 - Aktif (Otomatis menonaktifkan yang lain)</option>
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