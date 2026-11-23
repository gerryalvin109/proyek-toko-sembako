<?php
$page_title  = "Data Pengadaan";
$active_menu = "pengadaan";
require_once '../includes/header.php';

$res = mysqli_query($conn, "SELECT * FROM v_pengadaan ORDER BY idpengadaan DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Data Pengadaan</h4>
        <p class="text-muted small mb-0">Daftar pemesanan barang ke vendor</p>
    </div>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Pengadaan</a>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Vendor</th>
                    <th>Petugas</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">PPN</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): 
                    $is_selesai = ($r['status'] === 'S');
                ?>
                <tr>
                    <td class="fw-semibold">#<?= $r['idpengadaan'] ?></td>
                    <td><?= $r['timestamp'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['nama_vendor']) ?></td>
                    <td><?= htmlspecialchars($r['petugas']) ?></td>
                    <td class="text-end">Rp <?= number_format($r['subtotal_nilai'], 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($r['ppn'], 0, ',', '.') ?></td>
                    <td class="text-end fw-bold text-primary">Rp <?= number_format($r['total_nilai'], 0, ',', '.') ?></td>
                    <td class="text-center">
                        <span class="badge <?= $is_selesai ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= $is_selesai ? 'Selesai' : 'Diterima / Proses' ?>
                        </span>
                    </td>
                    <td class="text-center"><a href="detail.php?id=<?= $r['idpengadaan'] ?>" class="btn btn-sm btn-info text-white">Detail</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="9" class="text-center text-muted py-3">Belum ada data pengadaan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
