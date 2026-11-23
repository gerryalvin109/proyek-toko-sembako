<?php
$page_title  = "Master Barang";
$active_menu = "barang";
require_once '../includes/header.php';

$filter = isset($_GET['filter']) && $_GET['filter'] === 'aktif' ? 'aktif' : 'all';
$view   = $filter === 'aktif' ? 'v_barang_aktif' : 'v_barang_all';
$res    = mysqli_query($conn, "SELECT * FROM $view ORDER BY id_barang ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Master Barang</h4>
        <p class="text-muted mb-0">Kelola daftar barang sembako</p>
    </div>
    <div class="d-flex gap-2">
        <div class="btn-group">
            <a href="index.php?filter=all" class="btn <?= $filter === 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">Semua Data</a>
            <a href="index.php?filter=aktif" class="btn <?= $filter === 'aktif' ? 'btn-dark' : 'btn-outline-dark' ?>">Data Aktif</a>
        </div>
        <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Barang</a>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Satuan</th>
                    <th>Harga Pokok</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $r['id_barang'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($r['jenis'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['nama_satuan']) ?></td>
                    <td>Rp <?= number_format($r['harga_pokok'], 0, ',', '.') ?></td>
                    <td>
                        <span class="badge <?= ($r['status'] ?? 1) == 1 ? 'bg-success' : 'bg-secondary' ?>">
                            <?= ($r['status'] ?? 1) == 1 ? 'Aktif' : 'Non-Aktif' ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $r['id_barang'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $r['id_barang'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus barang ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data barang.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
