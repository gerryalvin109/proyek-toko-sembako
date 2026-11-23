<?php
$page_title  = "Edit Role";
$active_menu = "role";
require_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_role']);
    mysqli_query($conn, "UPDATE `role` SET nama_role='$nama' WHERE idrole=$id");
    header("Location: index.php");
    exit;
}
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `role` WHERE idrole=$id"));
?>
<h4 class="fw-bold mb-4">Edit Role</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Nama Role</label><input type="text" name="nama_role" class="form-control" value="<?= htmlspecialchars($data['nama_role'] ?? '') ?>" required></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
