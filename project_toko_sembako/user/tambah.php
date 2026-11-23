<?php
$page_title  = "Tambah User";
$active_menu = "user";
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $idrole   = (int)$_POST['idrole'];
    mysqli_query($conn, "INSERT INTO `user` (username, password, idrole) VALUES ('$username', SHA2('$password', 256), $idrole)");
    header("Location: index.php");
    exit;
}
$roles = mysqli_query($conn, "SELECT * FROM v_role");
?>
<h4 class="fw-bold mb-4">Tambah User</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="idrole" class="form-select" required>
                    <?php while ($rl = mysqli_fetch_assoc($roles)): ?>
                        <option value="<?= $rl['id_role'] ?>"><?= htmlspecialchars($rl['nama_role']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
