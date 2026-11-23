<?php
$page_title  = "Data Penjualan";
$active_menu = "penjualan";
require_once '../includes/header.php';
$res = mysqli_query($conn, "SELECT * FROM v_penjualan ORDER BY idpenjualan DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Data Penjualan</h4>
        <p class="text-muted small mb-0">Riwayat transaksi penjualan kasir</p>
    </div>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Penjualan</a>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Kasir</th>
                    <th>Margin</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">PPN</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td class="fw-semibold">#<?= $r['idpenjualan'] ?></td>
                    <td><?= $r['created_at'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['kasir']) ?></td>
                    <td><span class="badge bg-info text-dark"><?= $r['persen_margin'] ?>%</span></td>
                    <td class="text-end">Rp <?= number_format($r['subtotal_nilai'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($r['ppn'], 0, ',', '.') ?></td>
                    <td class="text-end fw-bold text-success">Rp <?= number_format($r['total_nilai'], 0, ',', '.') ?></td>
                    <td class="text-center"><a href="detail.php?id=<?= $r['idpenjualan'] ?>" class="btn btn-sm btn-info text-white">Detail</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" class="text-center text-muted py-3">Belum ada data penjualan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>