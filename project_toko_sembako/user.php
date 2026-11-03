<?php
require_once 'koneksi.php';

$pesan = ""; $error = "";

if (isset($_POST['aksi']) &&$_POST['aksi'] == 'tambah') {
    $id       = (int)$_POST['iduser'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);$password = mysqli_real_escape_string($conn,$_POST['password']);
    $idrole   = (int)$_POST['idrole'];

    $q = "INSERT INTO `user` (iduser, username, password, idrole) VALUES ($id, '$username', SHA2('$password', 256),$idrole)";
    if (mysqli_query($conn, $q))$pesan = "User berhasil ditambahkan!";
    else $error = "Gagal menambah user: " . mysqli_error($conn);
}

if (isset($_POST['aksi']) &&$_POST['aksi'] == 'edit') {
    $id       = (int)$_POST['iduser'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);$password = mysqli_real_escape_string($conn,$_POST['password']);
    $idrole   = (int)$_POST['idrole'];

    if (!empty($password)) {$q = "UPDATE `user` SET username='$username', password=SHA2('$password', 256), idrole=$idrole WHERE iduser=$id";
    } else {
        $q = "UPDATE `user` SET username='$username', idrole=$idrole WHERE iduser=$id";
    }
    if (mysqli_query($conn, $q))$pesan = "User berhasil diperbarui!";
    else $error = "Gagal mengubah user: " . mysqli_error($conn);
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM `user` WHERE iduser=$id")) $pesan = "User berhasil dihapus!";
    else $error = "User tidak dapat dihapus karena berelasi dengan data lain.";
}

$q_max = mysqli_query($conn, "SELECT COALESCE(MAX(iduser), 0) + 1 AS next_id FROM `user`");
$next_id = mysqli_fetch_assoc($q_max)['next_id'];

$list_role = [];
$q_r = mysqli_query($conn, "SELECT * FROM v_role");
while ($r = mysqli_fetch_assoc($q_r)) { $list_role[] =$r; }

$data_user = [];
$res = mysqli_query($conn, "SELECT * FROM v_user ORDER BY id_user ASC");
while ($row = mysqli_fetch_assoc($res)) { $data_user[] =$row; }

render_header("Master User");
render_sidebar("user");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan): ?><div class="alert alert-success alert-dismissible fade show"><?= $pesan ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Master User</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i>Tambah User</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID User</th>
                        <th>Username</th>
                        <th>ID Role</th>
                        <th>Nama Role</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_user as$row): ?>
                    <tr>
                        <td><?= $row['id_user'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= $row['id_role'] ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['nama_role']) ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_user'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="user.php?hapus=<?= $row['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT USER (DI LUAR TABLE) -->
<?php foreach ($data_user as$row): ?>
<div class="modal fade" id="modalEdit<?= $row['id_user'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Edit User #<?= $row['id_user'] ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="iduser" value="<?= $row['id_user'] ?>">
                <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required></div>
                <div class="mb-3"><label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-control"></div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="idrole" class="form-select">
                        <?php foreach ($list_role as$r): ?>
                            <option value="<?= $r['id_role'] ?>" <?= $row['id_role']==$r['id_role']?'selected':'' ?>><?=$r['nama_role'] ?></option>
                        <?php endforeach; ?>
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

<!-- MODAL TAMBAH USER -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content bg-white shadow">
            <div class="modal-header"><h5 class="modal-title fw-bold">Tambah User Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="aksi" value="tambah">
                <div class="mb-3"><label class="form-label">ID User</label><input type="number" name="iduser" class="form-control" value="<?= $next_id ?>" required></div>
                <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="idrole" class="form-select">
                        <?php foreach ($list_role as$r): ?>
                            <option value="<?= $r['id_role'] ?>"><?= $r['nama_role'] ?></option>
                        <?php endforeach; ?>
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