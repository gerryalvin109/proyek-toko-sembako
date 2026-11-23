<?php
$page_title  = "Tambah Role";
$active_menu = "role";
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_role']);
    mysqli_query($conn, "INSERT INTO `role` (nama_role) VALUES ('$nama')");
    header("Location: index.php");
    exit;
}
?>
<h4 class="fw-bold mb-4">Tambah Role</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Nama Role</label><input type="text" name="nama_role" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
