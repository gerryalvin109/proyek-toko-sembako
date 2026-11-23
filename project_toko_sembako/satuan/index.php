<?php
$page_title  = "Master Satuan";
$active_menu = "satuan";
require_once '../includes/header.php';

$filter = isset($_GET['filter']) && $_GET['filter'] === 'aktif' ? 'aktif' : 'all';
$view   = $filter === 'aktif' ? 'v_satuan_aktif' : 'v_satuan_all';
$res    = mysqli_query($conn, "SELECT * FROM $view ORDER BY id_satuan ASC");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Master Satuan</h4>
    <div class="d-flex gap-2">
        <div class="btn-group">
            <a href="index.php?filter=all" class="btn <?= $filter === 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua</a>
            <a href="index.php?filter=aktif" class="btn <?= $filter === 'aktif' ? 'btn-dark' : 'btn-outline-dark' ?>">Aktif</a>
        </div>
        <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Satuan</a>
    </div>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr><th>ID</th><th>Nama Satuan</th><th>Status</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $r['id_satuan'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['nama_satuan']) ?></td>
                    <td><span class="badge <?= ($r['status'] ?? 1) == 1 ? 'bg-success' : 'bg-secondary' ?>"><?= ($r['status'] ?? 1) == 1 ? 'Aktif' : 'Non-Aktif' ?></span></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $r['id_satuan'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $r['id_satuan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus satuan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data satuan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
