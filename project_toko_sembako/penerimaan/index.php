<?php
$page_title  = "Penerimaan";
$active_menu = "penerimaan";
require_once '../includes/header.php';

$status_filter = $_GET['status'] ?? 'semua';

$sql = "
    SELECT 
        p.idpengadaan,
        p.timestamp AS waktu_pengadaan,
        v.nama_vendor,
        p.status,
        COALESCE((SELECT SUM(dp.jumlah) FROM detail_pengadaan dp WHERE dp.idpengadaan = p.idpengadaan), 0) AS total_dipesan,
        COALESCE((
            SELECT SUM(dpr.jumlah_terima)
            FROM detail_penerimaan dpr
            JOIN penerimaan pr ON dpr.idpenerimaan = pr.idpenerimaan
            WHERE pr.idpengadaan = p.idpengadaan
        ), 0) AS total_diterima,
        (SELECT COUNT(*) FROM penerimaan pr WHERE pr.idpengadaan = p.idpengadaan) AS kali_terima,
        (SELECT MAX(pr.created_at) FROM penerimaan pr WHERE pr.idpengadaan = p.idpengadaan) AS terakhir_diterima
    FROM pengadaan p
    JOIN vendor v ON p.vendor_idvendor = v.idvendor
    ORDER BY p.idpengadaan DESC
";
$res = mysqli_query($conn, $sql);
$rows = [];
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) {
        $selesai = ($r['status'] === 'S' || ((int)$r['total_dipesan'] > 0 && (int)$r['total_diterima'] >= (int)$r['total_dipesan']));
        $r['is_selesai'] = $selesai;
        if ($status_filter === 'diterima' && $selesai) continue;
        if ($status_filter === 'selesai' && !$selesai) continue;
        $rows[] = $r;
    }
}
?>

<h4 class="fw-bold mb-3">Penerimaan</h4>

<!-- Filter Status: Semua / Diterima / Selesai -->
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="small text-muted me-1">Status</span>
    <a href="index.php?status=semua" class="btn btn-sm <?= $status_filter === 'semua' ? 'btn-primary' : 'btn-outline-secondary' ?>">Semua</a>
    <a href="index.php?status=diterima" class="btn btn-sm <?= $status_filter === 'diterima' ? 'btn-primary' : 'btn-outline-secondary' ?>">Diterima</a>
    <a href="index.php?status=selesai" class="btn btn-sm <?= $status_filter === 'selesai' ? 'btn-primary' : 'btn-outline-secondary' ?>">Selesai</a>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="small text-muted"><?= count($rows) ?> data</div>
    <a href="tambah.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah penerimaan</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Pengadaan</th>
                    <th>Waktu</th>
                    <th>Vendor</th>
                    <th>Status</th>
                    <th>Diterima</th>
                    <th class="text-center">Kali Terima</th>
                    <th>Terakhir Diterima</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rows) > 0): foreach ($rows as $r): 
                    $pesan  = (int)$r['total_dipesan'];
                    $terima = (int)$r['total_diterima'];
                    $persen = $pesan > 0 ? round(($terima / $pesan) * 100) : 0;
                ?>
                <tr>
                    <td class="fw-semibold">#<?= $r['idpengadaan'] ?></td>
                    <td><?= $r['waktu_pengadaan'] ?></td>
                    <td><?= htmlspecialchars($r['nama_vendor']) ?></td>
                    <td>
                        <span class="badge <?= $r['is_selesai'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= $r['is_selesai'] ? 'Selesai' : 'Diterima' ?>
                        </span>
                    </td>
                    <td style="min-width: 170px;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: <?= $persen ?>%"></div>
                            </div>
                            <span class="small"><?= $terima ?> / <?= $pesan ?> (<?= $persen ?>%)</span>
                        </div>
                    </td>
                    <td class="text-center"><?= $r['kali_terima'] ?></td>
                    <td><?= $r['terakhir_diterima'] ? $r['terakhir_diterima'] : '-' ?></td>
                    <td class="text-center">
                        <?php if (!$r['is_selesai']): ?>
                            <a href="tambah.php?idpengadaan=<?= $r['idpengadaan'] ?>" class="btn btn-sm btn-primary">Terima</a>
                        <?php else: ?>
                            <a href="../pengadaan/detail.php?id=<?= $r['idpengadaan'] ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
