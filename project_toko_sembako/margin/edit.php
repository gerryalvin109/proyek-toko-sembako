<?php
$page_title  = "Edit Margin";
$active_menu = "margin";
require_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $persen = (float)$_POST['persen'];
    mysqli_query($conn, "UPDATE margin_penjualan SET persen=$persen WHERE idmargin_penjualan=$id");
    header("Location: index.php");
    exit;
}
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM margin_penjualan WHERE idmargin_penjualan=$id"));
?>
<h4 class="fw-bold mb-4">Edit Margin</h4>
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label class="form-label">Persentase Margin (%)</label><input type="number" step="0.1" name="persen" class="form-control" value="<?= $data['persen'] ?? 0 ?>" required></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
