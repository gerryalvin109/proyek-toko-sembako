<?php
require_once 'koneksi.php';

$pesan = ""; $error = "";

// CREATE BARANG
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id     = (int)$_POST['idbarang'];
    $jenis  = mysqli_real_escape_string($conn, $_POST['jenis']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $satuan = (int)$_POST['idsatuan'];
    $harga  = (int)$_POST['harga'];
    $status = (int)$_POST['status'];

    $q = "INSERT INTO barang (idbarang, jenis, nama, idsatuan, status, harga) VALUES ($id, '$jenis', '$nama', $satuan, $status, $harga)";
    if (mysqli_query($conn, $q)) $pesan = "Barang berhasil ditambahkan!";
    else $error = "Gagal menambah barang: " . mysqli_error($conn);
}

// UPDATE BARANG
if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id     = (int)$_POST['idbarang'];
    $jenis  = mysqli_real_escape_string($conn, $_POST['jenis']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $satuan = (int)$_POST['idsatuan'];
    $harga  = (int)$_POST['harga'];
    $status = (int)$_POST['status'];

    $q = "UPDATE barang SET jenis='$jenis', nama='$nama', idsatuan=$satuan, harga=$harga, status=$status WHERE idbarang=$id";
    if (mysqli_query($conn, $q)) $pesan = "Data barang berhasil diperbarui!";
    else $error = "Gagal mengubah barang: " . mysqli_error($conn);
}

// DELETE BARANG
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM barang WHERE idbarang=$id")) $pesan = "Barang berhasil dihapus!";
    else $error = "Barang tidak dapat dihapus karena sedang berelasi dengan data transaksi.";
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'all';
$nama_view = ($mode == 'aktif') ? 'v_barang_aktif' : 'v_barang_all';

$q_max = mysqli_query($conn, "SELECT COALESCE(MAX(idbarang), 0) + 1 AS next_id FROM barang");
$next_id = mysqli_fetch_assoc($q_max)['next_id'];

// Ambil daftar satuan untuk form
$list_satuan = [];
$q_sat = mysqli_query($conn, "SELECT * FROM v_satuan_all");
while ($s = mysqli_fetch_assoc($q_sat)) { $list_satuan[] = $s; }

// Simpan data barang ke array agar Modal bisa ditaruh di luar <table>
$data_barang = [];
$res = mysqli_query($conn, "SELECT * FROM $nama_view ORDER BY id_barang ASC");
while ($row = mysqli_fetch_assoc($res)) { $data_barang[] = $row; }

render_header("Master Barang");
render_sidebar("barang");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan): ?><div class="alert alert-success alert-dismissible fade show"><?= $pesan ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Master Barang</h4>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <a href="barang.php?mode=all" class="btn <?= $mode == 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua Data</a>
                <a href="barang.php?mode=aktif" class="btn <?= $mode == 'aktif' ? 'btn-success' : 'btn-outline-success' ?>">Data Aktif</a>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Barang</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Jenis</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Harga Pokok</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_barang as $row): ?>
                    <tr>
                        <td><?= $row['id_barang'] ?></td>
                        <td><?= $row['nama_jenis'] ?> (<?= $row['kode_jenis'] ?>)</td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($row['nama_satuan']) ?></td>
                        <td>Rp <?= number_format($row['harga_pokok'], 0, ',', '.') ?></td>
                        <td><span class="badge <?= $row['ket_status'] == 'Aktif' ? 'bg-success' : 'bg-danger' ?>"><?= $row['ket_status'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_barang'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="barang.php?mode=<?= $mode ?>&hapus=<?= $row['id_barang'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus barang ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- KUMPULAN MODAL EDIT BARANG (DI LUAR TABLE AGAR TIDAK BUG TRANSPARAN) -->
<?php foreach ($data_barang as $row): ?>
<div class="modal fade" id="modalEdit<?= $row['id_barang'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Barang #<?= $row['id_barang'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="idbarang" value="<?= $row['id_barang'] ?>">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama_barang']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis" class="form-select">
                        <option value="M" <?= $row['kode_jenis']=='M'?'selected':'' ?>>M - Makanan / Pokok</option>
                        <option value="N" <?= $row['kode_jenis']=='N'?'selected':'' ?>>N - Minuman / Cair</option>
                        <option value="K" <?= $row['kode_jenis']=='K'?'selected':'' ?>>K - Kebersihan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select name="idsatuan" class="form-select">
                        <?php foreach ($list_satuan as $s): ?>
                            <option value="<?= $s['id_satuan'] ?>" <?= $row['id_satuan']==$s['id_satuan']?'selected':'' ?>><?= $s['nama_satuan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Pokok (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="<?= $row['harga_pokok'] ?>" required>
                </div>
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

<!-- MODAL TAMBAH BARANG -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="tambah">
                <div class="mb-3"><label class="form-label">ID Barang</label><input type="number" name="idbarang" class="form-control" value="<?= $next_id ?>" required></div>
                <div class="mb-3"><label class="form-label">Nama Barang</label><input type="text" name="nama" class="form-control" required></div>
                <div class="mb-3">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis" class="form-select">
                        <option value="M">M - Makanan / Pokok</option>
                        <option value="N">N - Minuman / Cair</option>
                        <option value="K">K - Kebersihan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select name="idsatuan" class="form-select">
                        <?php foreach ($list_satuan as $s): ?>
                            <option value="<?= $s['id_satuan'] ?>"><?= $s['nama_satuan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Harga Pokok (Rp)</label><input type="number" name="harga" class="form-control" required></div>
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