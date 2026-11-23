<?php
$page_title  = "Master Vendor";
$active_menu = "vendor";
require_once '../includes/header.php';

$filter = isset($_GET['filter']) && $_GET['filter'] === 'aktif' ? 'aktif' : 'all';
$view   = $filter === 'aktif' ? 'v_vendor_aktif' : 'v_vendor_all';
$res    = mysqli_query($conn, "SELECT * FROM $view ORDER BY id_vendor ASC");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Master Vendor</h4>
    <div class="d-flex gap-2">
        <div class="btn-group">
            <a href="index.php?filter=all" class="btn <?= $filter === 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua</a>
            <a href="index.php?filter=aktif" class="btn <?= $filter === 'aktif' ? 'btn-dark' : 'btn-outline-dark' ?>">Aktif</a>
        </div>
        <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Vendor</a>
    </div>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr><th>ID</th><th>Nama Vendor</th><th>Badan Hukum</th><th>Status</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $r['id_vendor'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['nama_vendor']) ?></td>
                    <td><?= htmlspecialchars($r['badan_hukum'] ?? '-') ?></td>
                    <td><span class="badge <?= ($r['status'] ?? 'A') == 'A' || ($r['status'] ?? 1) == 1 ? 'bg-success' : 'bg-secondary' ?>"><?= htmlspecialchars($r['status'] ?? 'Aktif') ?></span></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $r['id_vendor'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $r['id_vendor'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus vendor ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data vendor.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
