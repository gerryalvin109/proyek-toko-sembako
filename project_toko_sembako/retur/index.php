<?php
$page_title  = "Retur";
$active_menu = "retur";
require_once '../includes/header.php';

$sql = "
    SELECT 
        r.idretur,
        r.created_at,
        r.idpenerimaan,
        pr.idpengadaan,
        v.nama_vendor,
        u.username AS dibuat_oleh,
        COALESCE(SUM(dr.jumlah), 0) AS total_jumlah,
        COALESCE(SUM(dr.jumlah * dpr.harga_satuan_terima), 0) AS total_nilai
    FROM retur r
    JOIN penerimaan pr ON r.idpenerimaan = pr.idpenerimaan
    JOIN pengadaan p ON pr.idpengadaan = p.idpengadaan
    JOIN vendor v ON p.vendor_idvendor = v.idvendor
    JOIN `user` u ON r.iduser = u.iduser
    LEFT JOIN detail_retur dr ON r.idretur = dr.idretur
    LEFT JOIN detail_penerimaan dpr ON dr.iddetail_penerimaan = dpr.iddetail_penerimaan
    GROUP BY r.idretur, r.created_at, r.idpenerimaan, pr.idpengadaan, v.nama_vendor, u.username
    ORDER BY r.idretur DESC
";
$res = mysqli_query($conn, $sql);
$total_data = $res ? mysqli_num_rows($res) : 0;
?>

<h4 class="fw-bold mb-2">Retur</h4>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="small text-muted"><?= $total_data ?> data</div>
    <a href="tambah.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah retur</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Penerimaan</th>
                    <th>Pengadaan</th>
                    <th>Vendor</th>
                    <th>Dibuat Oleh</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Nilai</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res && $total_data > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td>#<?= $r['idretur'] ?></td>
                    <td><?= $r['created_at'] ?></td>
                    <td>#<?= $r['idpenerimaan'] ?></td>
                    <td>#<?= $r['idpengadaan'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['nama_vendor']) ?></td>
                    <td><?= htmlspecialchars($r['dibuat_oleh']) ?></td>
                    <td class="text-center fw-bold text-danger"><?= $r['total_jumlah'] ?></td>
                    <td class="text-end fw-semibold">Rp <?= number_format($r['total_nilai'], 0, ',', '.') ?></td>
                    <td class="text-center"><a href="detail.php?id=<?= $r['idretur'] ?>" class="btn btn-sm btn-info text-white">Detail</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada retur.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
