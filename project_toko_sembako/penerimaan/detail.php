<?php
$page_title  = "Detail Penerimaan";
$active_menu = "penerimaan";
require_once '../includes/header.php';
$id  = (int)($_GET['id'] ?? 0);
$res = mysqli_query($conn, "SELECT * FROM v_detail_penerimaan WHERE idpenerimaan = $id");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Detail Penerimaan #<?= $id ?></h4>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr><th>ID Detail</th><th>Nama Barang</th><th>Jumlah Terima</th><th>Harga Satuan</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td>#<?= $r['iddetail_penerimaan'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['nama_barang']) ?></td>
                    <td><?= $r['jumlah_terima'] ?></td>
                    <td>Rp <?= number_format($r['harga_satuan_terima'], 0, ',', '.') ?></td>
                    <td class="fw-bold text-success">Rp <?= number_format($r['sub_total_terima'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada rincian.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
