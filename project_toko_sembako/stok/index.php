<?php
$page_title  = "Kartu Stok";
$active_menu = "stok";
require_once '../includes/header.php';

// Ambil posisi stok tersedia saat ini menggunakan Function fn_cek_stok_barang
$q_stok_barang = mysqli_query($conn, "
    SELECT b.idbarang, b.nama, s.nama_satuan, b.harga, fn_cek_stok_barang(b.idbarang) AS stok_tersedia
    FROM barang b
    LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
    WHERE b.status = 1
    ORDER BY b.idbarang ASC
");

// Ambil seluruh riwayat mutasi kartu stok
$q_log = mysqli_query($conn, "
    SELECT * FROM v_kartu_stok
    ORDER BY idkartu_stok DESC
");
?>

<div class="mb-4">
    <h4 class="fw-bold mb-0">Monitoring & Kartu Stok</h4>
    <p class="text-muted small mb-0">Posisi stok tersedia saat ini dan riwayat mutasi keluar-masuk (otomatis dari transaksi)</p>
</div>

<!-- Tabel Posisi Stok Barang Saat Ini -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-success"></i>Posisi Stok Barang Saat Ini</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga Pokok</th>
                    <th class="text-center" style="width: 150px;">Stok Tersedia</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($q_stok_barang && mysqli_num_rows($q_stok_barang) > 0): while ($sb = mysqli_fetch_assoc($q_stok_barang)): ?>
                <tr>
                    <td><?= $sb['idbarang'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($sb['nama']) ?></td>
                    <td><?= htmlspecialchars($sb['nama_satuan'] ?? '-') ?></td>
                    <td>Rp <?= number_format($sb['harga'], 0, ',', '.') ?></td>
                    <td class="text-center">
                        <span class="badge <?= $sb['stok_tersedia'] > 0 ? 'bg-primary' : 'bg-danger' ?> fs-6 px-3">
                            <?= $sb['stok_tersedia'] ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data barang aktif.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tabel Log Riwayat Kartu Stok -->
<div class="card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Log Riwayat Kartu Stok</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Sisa Stok</th>
                    <th>ID Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($q_log && mysqli_num_rows($q_log) > 0): while ($r = mysqli_fetch_assoc($q_log)): ?>
                <tr>
                    <td>#<?= $r['idkartu_stok'] ?></td>
                    <td><?= $r['created_at'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['nama_barang']) ?></td>
                    <td>
                        <?php if ($r['jenis_transaksi'] === 'M'): ?>
                            <span class="badge bg-success">M - Masuk</span>
                        <?php else: ?>
                            <span class="badge bg-danger">K - Keluar</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-success fw-semibold">+<?= $r['masuk'] ?></td>
                    <td class="text-danger fw-semibold">-<?= $r['keluar'] ?></td>
                    <td class="fw-bold"><?= $r['stock'] ?></td>
                    <td>#<?= $r['idtransaksi'] ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" class="text-center text-muted py-3">Belum ada riwayat mutasi kartu stok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>