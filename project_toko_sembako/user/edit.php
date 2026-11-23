<?php
$page_title  = "Edit User";
$active_menu = "user";
require_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $idrole   = (int)$_POST['idrole'];
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        mysqli_query($conn, "UPDATE `user` SET username='$username', password=SHA2('$password', 256), idrole=$idrole WHERE iduser=$id");
    } else {
        mysqli_query($conn, "UPDATE `user` SET username='$username', idrole=$idrole WHERE iduser=$id");
    }
    header("Location: index.php");
    exit;
}
$data  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `user` WHERE iduser=$id"));
$roles = mysqli_query($conn, "SELECT * FROM v_role");
?>
<h4 class="fw-bold mb-4">Edit User</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username'] ?? '') ?>" required></div>
            <div class="mb-3"><label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-control"></div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="idrole" class="form-select" required>
                    <?php while ($rl = mysqli_fetch_assoc($roles)): ?>
                        <option value="<?= $rl['id_role'] ?>" <?= ($data['idrole'] ?? 0) == $rl['id_role'] ? 'selected' : '' ?>><?= htmlspecialchars($rl['nama_role']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
