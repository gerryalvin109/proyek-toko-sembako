<?php
$page_title  = "Detail Pengadaan";
$active_menu = "pengadaan";
require_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);

$q_head = mysqli_query($conn, "SELECT * FROM v_pengadaan WHERE idpengadaan = $id LIMIT 1");
$head   = $q_head ? mysqli_fetch_assoc($q_head) : null;

if (!$head) {
    echo "<div class='alert alert-warning'>Data pengadaan #$id tidak ditemukan.</div><a href='index.php' class='btn btn-secondary'>Kembali</a>";
    require_once '../includes/footer.php';
    exit;
}

$sql_items = "
    SELECT 
        dp.idbarang,
        b.nama AS nama_barang,
        s.nama_satuan,
        MAX(dp.harga_satuan) AS harga_satuan,
        SUM(dp.jumlah) AS jumlah,
        SUM(dp.sub_total) AS sub_total,
        COALESCE((
            SELECT SUM(dpr.jumlah_terima)
            FROM detail_penerimaan dpr
            JOIN penerimaan pr ON dpr.idpenerimaan = pr.idpenerimaan
            WHERE pr.idpengadaan = $id AND dpr.barang_idbarang = dp.idbarang
        ), 0) AS diterima
    FROM detail_pengadaan dp
    JOIN barang b ON dp.idbarang = b.idbarang
    JOIN satuan s ON b.idsatuan = s.idsatuan
    WHERE dp.idpengadaan = $id
    GROUP BY dp.idbarang, b.nama, s.nama_satuan
";
$res_items = mysqli_query($conn, $sql_items);

$items = [];
$total_pesan  = 0;
$total_terima = 0;
if ($res_items) {
    while ($r = mysqli_fetch_assoc($res_items)) {
        $r['sisa'] = max(0, (int)$r['jumlah'] - (int)$r['diterima']);
        $total_pesan  += (int)$r['jumlah'];
        $total_terima += (int)$r['diterima'];
        $items[] = $r;
    }
}
$persen = $total_pesan > 0 ? round(($total_terima / $total_pesan) * 100) : 0;
$is_selesai = ($head['status'] === 'S' || ($total_pesan > 0 && $total_terima >= $total_pesan));
?>

<h4 class="fw-bold mb-3">Pengadaan #<?= $id ?></h4>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'created'): ?>
    <div class="alert alert-success py-2">Pengadaan #<?= $id ?> berhasil dibuat.</div>
<?php endif; ?>

<!-- 5 Kartu Info -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card h-100 border">
            <div class="card-body py-3">
                <div class="small text-muted text-uppercase">Vendor</div>
                <div class="fw-semibold mt-1"><?= htmlspecialchars($head['nama_vendor']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card h-100 border">
            <div class="card-body py-3">
                <div class="small text-muted text-uppercase">Dibuat Oleh</div>
                <div class="fw-semibold mt-1"><?= htmlspecialchars($head['petugas']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border">
            <div class="card-body py-3">
                <div class="small text-muted text-uppercase">Waktu</div>
                <div class="fw-semibold mt-1"><?= $head['timestamp'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card h-100 border">
            <div class="card-body py-3">
                <div class="small text-muted text-uppercase">Status</div>
                <div class="mt-1">
                    <span class="badge <?= $is_selesai ? 'bg-success' : 'bg-warning text-dark' ?>">
                        <?= $is_selesai ? 'Selesai' : 'Diterima' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card h-100 border">
            <div class="card-body py-3">
                <div class="small text-muted text-uppercase">Diterima</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <div class="progress flex-grow-1" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: <?= $persen ?>%"></div>
                    </div>
                    <span class="small fw-semibold"><?= $total_terima ?>/<?= $total_pesan ?> (<?= $persen ?>%)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Item Pengadaan -->
<div class="card mb-4">
    <div class="card-body table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Barang</th>
                    <th>Satuan</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Sub Total</th>
                    <th class="text-center">Diterima</th>
                    <th class="text-center">Sisa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($items) > 0): foreach ($items as $it): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($it['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($it['nama_satuan']) ?></td>
                    <td class="text-end">Rp <?= number_format($it['harga_satuan'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= $it['jumlah'] ?></td>
                    <td class="text-end">Rp <?= number_format($it['sub_total'], 0, ',', '.') ?></td>
                    <td class="text-center text-success fw-semibold"><?= $it['diterima'] ?></td>
                    <td class="text-center fw-bold <?= $it['sisa'] > 0 ? 'text-danger' : 'text-muted' ?>"><?= $it['sisa'] ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada rincian barang.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-semibold">Subtotal</td>
                    <td class="text-end">Rp <?= number_format($head['subtotal_nilai'], 0, ',', '.') ?></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-semibold">PPN</td>
                    <td class="text-end">Rp <?= number_format($head['ppn'], 0, ',', '.') ?></td>
                    <td colspan="2"></td>
                </tr>
                <tr class="table-light">
                    <td colspan="4" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold text-primary">Rp <?= number_format($head['total_nilai'], 0, ',', '.') ?></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Tombol Aksi Bawah -->
<div class="d-flex justify-content-between align-items-center">
    <a href="index.php" class="btn btn-secondary">Kembali</a>
    <div class="d-flex gap-2">
        <a href="../penerimaan/index.php" class="btn btn-outline-secondary">Riwayat penerimaan</a>
        <?php if (!$is_selesai): ?>
            <a href="../penerimaan/tambah.php?idpengadaan=<?= $id ?>" class="btn btn-primary">Terima barang</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>