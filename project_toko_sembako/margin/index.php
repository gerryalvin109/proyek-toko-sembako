<?php
$page_title  = "Margin Penjualan";
$active_menu = "margin";
require_once '../includes/header.php';

$res = mysqli_query($conn, "SELECT * FROM v_margin_all ORDER BY id_margin DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Margin Penjualan</h4>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Margin</a>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr><th>ID</th><th>Persentase</th><th>Status</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $r['id_margin'] ?></td>
                    <td class="fw-bold"><?= $r['persen'] ?>%</td>
                    <td><span class="badge <?= $r['status'] == 1 ? 'bg-success' : 'bg-secondary' ?>"><?= $r['status'] == 1 ? 'Aktif' : 'Non-Aktif' ?></span></td>
                    <td class="text-center">
                        <?php if ($r['status'] != 1): ?>
                            <a href="aktifkan.php?id=<?= $r['id_margin'] ?>" class="btn btn-sm btn-success">Aktifkan</a>
                        <?php endif; ?>
                        <a href="edit.php?id=<?= $r['id_margin'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $r['id_margin'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus margin ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data margin.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
