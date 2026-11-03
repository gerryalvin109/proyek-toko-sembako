<?php
require_once 'koneksi.php';

$pesan = ""; $error = "";

if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id   = (int)$_POST['idrole'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_role']);
    if (mysqli_query($conn, "INSERT INTO role (idrole, nama_role) VALUES ($id, '$nama')")) $pesan = "Role berhasil ditambahkan!";
    else $error = "Gagal menambah role: " . mysqli_error($conn);
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id   = (int)$_POST['idrole'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_role']);
    if (mysqli_query($conn, "UPDATE role SET nama_role='$nama' WHERE idrole=$id")) $pesan = "Role berhasil diperbarui!";
    else $error = "Gagal mengubah role: " . mysqli_error($conn);
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM role WHERE idrole=$id")) $pesan = "Role berhasil dihapus!";
    else $error = "Role tidak dapat dihapus karena sedang digunakan oleh User.";
}

$q_max = mysqli_query($conn, "SELECT COALESCE(MAX(idrole), 0) + 1 AS next_id FROM role");
$next_id = mysqli_fetch_assoc($q_max)['next_id'];

$data_role = [];
$res = mysqli_query($conn, "SELECT * FROM v_role ORDER BY id_role ASC");
while ($row = mysqli_fetch_assoc($res)) { $data_role[] = $row; }

render_header("Master Role");
render_sidebar("role");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan): ?><div class="alert alert-success alert-dismissible fade show"><?= $pesan ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Master Role</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Role</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID Role</th>
                        <th>Nama Role</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_role as $row): ?>
                    <tr>
                        <td><?= $row['id_role'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nama_role']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_role'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="role.php?hapus=<?= $row['id_role'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus role ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT ROLE (DI LUAR TABLE) -->
<?php foreach ($data_role as $row): ?>
<div class="modal fade" id="modalEdit<?= $row['id_role'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Edit Role #<?= $row['id_role'] ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="idrole" value="<?= $row['id_role'] ?>">
                <div class="mb-3"><label class="form-label">Nama Role</label><input type="text" name="nama_role" class="form-control" value="<?= htmlspecialchars($row['nama_role']) ?>" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<!-- MODAL TAMBAH ROLE -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Role Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="tambah">
                <div class="mb-3"><label class="form-label">ID Role</label><input type="number" name="idrole" class="form-control" value="<?= $next_id ?>" required></div>
                <div class="mb-3"><label class="form-label">Nama Role</label><input type="text" name="nama_role" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php render_footer(); ?>