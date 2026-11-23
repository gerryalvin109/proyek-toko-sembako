<?php
$page_title  = "Monitoring & Kartu Stok";
$active_menu = "stok";
require_once '../includes/header.php';

$q_stok = mysqli_query($conn, "SELECT id_barang, nama_barang, nama_satuan, harga_pokok, fn_cek_stok_barang(id_barang) AS stok_akhir FROM v_barang_aktif ORDER BY id_barang ASC");
$q_ks   = mysqli_query($conn, "SELECT * FROM v_kartu_stok ORDER BY idkartu_stok DESC LIMIT 30");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Monitoring & Kartu Stok</h4>
        <p class="text-muted mb-0">Posisi stok tersedia saat ini dan riwayat mutasi keluar-masuk</p>
    </div>
    <a href="../penerimaan/tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Input Barang Masuk</a>
</div>

<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-success"></i>Posisi Stok Barang Saat Ini</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr><th>ID</th><th>Nama Barang</th><th>Satuan</th><th>Harga Pokok</th><th class="text-center">Stok Tersedia</th></tr>
            </thead>
            <tbody>
                <?php if ($q_stok): while ($s = mysqli_fetch_assoc($q_stok)): ?>
                <tr>
                    <td><?= $s['id_barang'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($s['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($s['nama_satuan']) ?></td>
                    <td>Rp <?= number_format($s['harga_pokok'], 0, ',', '.') ?></td>
                    <td class="text-center"><span class="badge <?= $s['stok_akhir'] > 0 ? 'bg-primary' : 'bg-secondary' ?> fs-6"><?= $s['stok_akhir'] ?></span></td>
                </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Log Riwayat Kartu Stok</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr><th>ID</th><th>Waktu</th><th>Nama Barang</th><th>Jenis</th><th>Masuk</th><th>Keluar</th><th>Sisa Stok</th><th>ID Transaksi</th></tr>
            </thead>
            <tbody>
                <?php if ($q_ks && mysqli_num_rows($q_ks) > 0): while ($k = mysqli_fetch_assoc($q_ks)): ?>
                <tr>
                    <td>#<?= $k['idkartu_stok'] ?></td>
                    <td><?= $k['created_at'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($k['nama_barang']) ?></td>
                    <td><span class="badge <?= $k['jenis_transaksi'] === 'M' ? 'bg-success' : 'bg-danger' ?>"><?= $k['jenis_transaksi'] === 'M' ? 'M - Masuk' : 'K - Keluar' ?></span></td>
                    <td class="text-success fw-bold">+<?= $k['masuk'] ?></td>
                    <td class="text-danger fw-bold">-<?= $k['keluar'] ?></td>
                    <td class="fw-bold bg-light"><?= $k['stock'] ?></td>
                    <td>#<?= $k['idtransaksi'] ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" class="text-center text-muted py-3">Belum ada riwayat mutasi stok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
