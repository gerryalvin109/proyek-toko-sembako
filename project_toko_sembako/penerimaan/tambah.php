<?php
$page_title  = "Tambah Penerimaan";
$active_menu = "penerimaan";
require_once '../includes/header.php';

$idpengadaan = (int)($_GET['idpengadaan'] ?? 0);
$pesan = "";
$error = "";

// Proses simpan penerimaan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idpengadaan > 0) {
    $iduser     = (int)$_SESSION['iduser'];
    $terima_arr = $_POST['terima'] ?? [];
    $harga_arr  = $_POST['harga'] ?? [];

    $valid_items = [];
    foreach ($terima_arr as $idbarang => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            $valid_items[(int)$idbarang] = [
                'qty'   => $qty,
                'harga' => (int)($harga_arr[$idbarang] ?? 0)
            ];
        }
    }

    if (count($valid_items) === 0) {
        $error = "Masukkan minimal 1 jumlah barang yang diterima.";
    } else {
        mysqli_begin_transaction($conn);
        try {
            // Status awal 'D' = Diterima (belum sepenuhnya)
            mysqli_query($conn, "INSERT INTO penerimaan (status, idpengadaan, iduser) VALUES ('D', $idpengadaan, $iduser)");
            $idpenerimaan = mysqli_insert_id($conn);

            foreach ($valid_items as $idbarang => $it) {
                $qty   = $it['qty'];
                $harga = $it['harga'];
                $sub   = $qty * $harga;
                mysqli_query($conn, "INSERT INTO detail_penerimaan (idpenerimaan, barang_idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima) VALUES ($idpenerimaan, $idbarang, $qty, $harga, $sub)");
            }

            // Panggil Stored Procedure untuk update status otomatis ('D' atau 'S')
            mysqli_query($conn, "CALL sp_update_status_pengadaan($idpengadaan)");
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) {;}

            mysqli_commit($conn);
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Gagal menyimpan penerimaan: " . $e->getMessage();
        }
    }
}

// Jika belum memilih idpengadaan, tampilkan daftar pengadaan yang masih ada sisa
if ($idpengadaan <= 0):
    $q_list = mysqli_query($conn, "
        SELECT 
            p.idpengadaan,
            p.timestamp,
            v.nama_vendor,
            COALESCE((SELECT SUM(dp.jumlah) FROM detail_pengadaan dp WHERE dp.idpengadaan = p.idpengadaan), 0) AS dipesan,
            COALESCE((SELECT SUM(dpr.jumlah_terima) FROM detail_penerimaan dpr JOIN penerimaan pr ON dpr.idpenerimaan = pr.idpenerimaan WHERE pr.idpengadaan = p.idpengadaan), 0) AS diterima
        FROM pengadaan p
        JOIN vendor v ON p.vendor_idvendor = v.idvendor
        HAVING (dipesan - diterima) > 0
        ORDER BY p.idpengadaan DESC
    ");
?>
<h4 class="fw-bold mb-2">Tambah penerimaan</h4>
<p class="text-muted small mb-3">Pilih pengadaan yang barangnya akan diterima</p>
<div class="card mb-3">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Pengadaan</th>
                    <th>Waktu</th>
                    <th>Vendor</th>
                    <th class="text-center">Dipesan</th>
                    <th class="text-center">Sudah Diterima</th>
                    <th class="text-center">Sisa</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($q_list && mysqli_num_rows($q_list) > 0): while ($r = mysqli_fetch_assoc($q_list)): ?>
                <tr>
                    <td class="fw-semibold">#<?= $r['idpengadaan'] ?></td>
                    <td><?= $r['timestamp'] ?></td>
                    <td><?= htmlspecialchars($r['nama_vendor']) ?></td>
                    <td class="text-center"><?= $r['dipesan'] ?></td>
                    <td class="text-center"><?= $r['diterima'] ?></td>
                    <td class="text-center fw-bold text-danger"><?= $r['dipesan'] - $r['diterima'] ?></td>
                    <td class="text-center"><a href="tambah.php?idpengadaan=<?= $r['idpengadaan'] ?>" class="btn btn-sm btn-primary">Terima</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pengadaan yang menunggu penerimaan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<a href="index.php" class="btn btn-secondary">Kembali</a>

<?php
else:
    $head = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM v_pengadaan WHERE idpengadaan = $idpengadaan LIMIT 1"));
    $sql_items = "
        SELECT 
            dp.idbarang,
            b.nama AS nama_barang,
            s.nama_satuan,
            MAX(dp.harga_satuan) AS harga_satuan,
            SUM(dp.jumlah) AS dipesan,
            COALESCE((
                SELECT SUM(dpr.jumlah_terima)
                FROM detail_penerimaan dpr
                JOIN penerimaan pr ON dpr.idpenerimaan = pr.idpenerimaan
                WHERE pr.idpengadaan = $idpengadaan AND dpr.barang_idbarang = dp.idbarang
            ), 0) AS sudah_diterima
        FROM detail_pengadaan dp
        JOIN barang b ON dp.idbarang = b.idbarang
        JOIN satuan s ON b.idsatuan = s.idsatuan
        WHERE dp.idpengadaan = $idpengadaan
        GROUP BY dp.idbarang, b.nama, s.nama_satuan
    ";
    $res_items = mysqli_query($conn, $sql_items);
    $items = [];
    $tot_pesan = 0; $tot_terima = 0;
    while ($it = mysqli_fetch_assoc($res_items)) {
        $it['sisa'] = max(0, (int)$it['dipesan'] - (int)$it['sudah_diterima']);
        $tot_pesan  += (int)$it['dipesan'];
        $tot_terima += (int)$it['sudah_diterima'];
        $items[] = $it;
    }
    $persen = $tot_pesan > 0 ? round(($tot_terima / $tot_pesan) * 100) : 0;
?>

<h4 class="fw-bold mb-3">Tambah penerimaan</h4>
<?php if ($error): ?><div class="alert alert-danger py-2"><?= $error ?></div><?php endif; ?>

<!-- 5 Kartu Info -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card h-100 border"><div class="card-body py-3">
            <div class="small text-muted text-uppercase">Pengadaan</div>
            <div class="fw-bold mt-1">#<?= $idpengadaan ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border"><div class="card-body py-3">
            <div class="small text-muted text-uppercase">Vendor</div>
            <div class="fw-semibold mt-1"><?= htmlspecialchars($head['nama_vendor'] ?? '-') ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border"><div class="card-body py-3">
            <div class="small text-muted text-uppercase">Waktu</div>
            <div class="fw-semibold mt-1"><?= $head['timestamp'] ?? '-' ?></div>
        </div></div>
    </div>
    <div class="col-md-2">
        <div class="card h-100 border"><div class="card-body py-3">
            <div class="small text-muted text-uppercase">Diterima</div>
            <div class="d-flex align-items-center gap-2 mt-1">
                <div class="progress flex-grow-1" style="height: 8px;"><div class="progress-bar bg-primary" style="width: <?= $persen ?>%"></div></div>
                <span class="small"><?= $tot_terima ?>/<?= $tot_pesan ?> (<?= $persen ?>%)</span>
            </div>
        </div></div>
    </div>
    <div class="col-md-2">
        <div class="card h-100 border"><div class="card-body py-3">
            <div class="small text-muted text-uppercase">Penerima</div>
            <div class="fw-semibold mt-1"><?= htmlspecialchars($_SESSION['username']) ?></div>
        </div></div>
    </div>
</div>

<form method="POST">
    <div class="card mb-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barang</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Dipesan</th>
                        <th class="text-center">Sudah Diterima</th>
                        <th class="text-center">Sisa</th>
                        <th class="text-center" style="width: 150px;">Terima Sekarang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($it['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($it['nama_satuan']) ?></td>
                        <td class="text-end">
                            Rp <?= number_format($it['harga_satuan'], 0, ',', '.') ?>
                            <input type="hidden" name="harga[<?= $it['idbarang'] ?>]" value="<?= $it['harga_satuan'] ?>">
                        </td>
                        <td class="text-center"><?= $it['dipesan'] ?></td>
                        <td class="text-center"><?= $it['sudah_diterima'] ?></td>
                        <td class="text-center fw-bold"><?= $it['sisa'] ?></td>
                        <td>
                            <input type="number" name="terima[<?= $it['idbarang'] ?>]" class="form-control form-control-sm text-end input-terima" min="0" max="<?= $it['sisa'] ?>" data-sisa="<?= $it['sisa'] ?>" value="0" <?= $it['sisa'] == 0 ? 'readonly' : '' ?>>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan penerimaan</button>
        <button type="button" class="btn btn-outline-secondary" onclick="terimaSemuaSisa()">Terima semua sisa</button>
        <a href="index.php" class="btn btn-light border">Batal</a>
    </div>
</form>

<script>
function terimaSemuaSisa() {
    document.querySelectorAll('.input-terima').forEach(function(el) {
        el.value = el.getAttribute('data-sisa');
    });
}
</script>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>