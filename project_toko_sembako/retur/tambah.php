<?php
$page_title  = "Tambah Retur";
$active_menu = "retur";
require_once '../includes/header.php';

$idpenerimaan = (int)($_GET['idpenerimaan'] ?? 0);
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idpenerimaan > 0) {
    $iduser     = (int)$_SESSION['iduser'];
    $jumlah_arr = $_POST['jumlah'] ?? [];
    $alasan_arr = $_POST['alasan'] ?? [];

    $valid_items = [];
    foreach ($jumlah_arr as $iddetail_penerimaan => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            $valid_items[(int)$iddetail_penerimaan] = [
                'qty'    => $qty,
                'alasan' => mysqli_real_escape_string($conn, trim($alasan_arr[$iddetail_penerimaan] ?? 'Barang rusak/tidak sesuai'))
            ];
        }
    }

    if (count($valid_items) === 0) {
        $error = "Masukkan minimal 1 jumlah barang yang akan diretur.";
    } else {
        mysqli_begin_transaction($conn);
        try {
            mysqli_query($conn, "INSERT INTO retur (idpenerimaan, iduser) VALUES ($idpenerimaan, $iduser)");
            $idretur = mysqli_insert_id($conn);

            foreach ($valid_items as $iddpr => $it) {
                $qty    = $it['qty'];
                $alasan = $it['alasan'];
                mysqli_query($conn, "INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan) VALUES ($qty, '$alasan', $idretur, $iddpr)");
            }

            mysqli_commit($conn);
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Gagal menyimpan retur: " . $e->getMessage();
        }
    }
}

if ($idpenerimaan <= 0):
    $sql_list = "
        SELECT 
            pr.idpenerimaan,
            pr.created_at AS waktu,
            pr.idpengadaan,
            v.nama_vendor,
            COALESCE((SELECT SUM(dpr.jumlah_terima) FROM detail_penerimaan dpr WHERE dpr.idpenerimaan = pr.idpenerimaan), 0) AS diterima,
            COALESCE((
                SELECT SUM(dr.jumlah)
                FROM detail_retur dr
                JOIN detail_penerimaan dpr ON dr.iddetail_penerimaan = dpr.iddetail_penerimaan
                WHERE dpr.idpenerimaan = pr.idpenerimaan
            ), 0) AS sudah_diretur
        FROM penerimaan pr
        JOIN pengadaan p ON pr.idpengadaan = p.idpengadaan
        JOIN vendor v ON p.vendor_idvendor = v.idvendor
        HAVING (diterima - sudah_diretur) > 0
        ORDER BY pr.idpenerimaan DESC
    ";
    $res_list = mysqli_query($conn, $sql_list);
    $jml_penerimaan = $res_list ? mysqli_num_rows($res_list) : 0;
?>

<h4 class="fw-bold mb-1">Tambah retur</h4>
<p class="text-muted small mb-3">Pilih penerimaan yang barangnya akan diretur (<?= $jml_penerimaan ?> penerimaan)</p>

<div class="card mb-3">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Penerimaan</th>
                    <th>Waktu</th>
                    <th>Pengadaan</th>
                    <th>Vendor</th>
                    <th class="text-center">Diterima</th>
                    <th class="text-center">Sudah Diretur</th>
                    <th class="text-center">Sisa Retur</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res_list && $jml_penerimaan > 0): while ($r = mysqli_fetch_assoc($res_list)): ?>
                <tr>
                    <td class="fw-semibold">#<?= $r['idpenerimaan'] ?></td>
                    <td><?= $r['waktu'] ?></td>
                    <td>#<?= $r['idpengadaan'] ?></td>
                    <td><?= htmlspecialchars($r['nama_vendor']) ?></td>
                    <td class="text-center"><?= $r['diterima'] ?></td>
                    <td class="text-center"><?= $r['sudah_diretur'] ?></td>
                    <td class="text-center fw-bold text-primary"><?= $r['diterima'] - $r['sudah_diretur'] ?></td>
                    <td class="text-center"><a href="tambah.php?idpenerimaan=<?= $r['idpenerimaan'] ?>" class="btn btn-sm btn-primary">Retur</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada penerimaan yang bisa diretur.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<a href="index.php" class="btn btn-secondary">Kembali</a>

<?php
else:
    $sql_items = "
        SELECT 
            dpr.iddetail_penerimaan,
            b.nama AS nama_barang,
            dpr.jumlah_terima,
            COALESCE((SELECT SUM(dr.jumlah) FROM detail_retur dr WHERE dr.iddetail_penerimaan = dpr.iddetail_penerimaan), 0) AS sudah_diretur
        FROM detail_penerimaan dpr
        JOIN barang b ON dpr.barang_idbarang = b.idbarang
        WHERE dpr.idpenerimaan = $idpenerimaan
    ";
    $res_items = mysqli_query($conn, $sql_items);
?>

<h4 class="fw-bold mb-1">Input Retur untuk Penerimaan #<?= $idpenerimaan ?></h4>
<p class="text-muted small mb-3">Isi jumlah barang yang diretur beserta alasannya</p>
<?php if ($error): ?><div class="alert alert-danger py-2"><?= $error ?></div><?php endif; ?>

<form method="POST">
    <div class="card mb-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barang</th>
                        <th class="text-center">Diterima</th>
                        <th class="text-center">Sudah Diretur</th>
                        <th class="text-center">Sisa Retur</th>
                        <th style="width: 130px;">Jumlah Retur</th>
                        <th>Alasan Retur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($it = mysqli_fetch_assoc($res_items)): 
                        $sisa = max(0, (int)$it['jumlah_terima'] - (int)$it['sudah_diretur']);
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($it['nama_barang']) ?></td>
                        <td class="text-center"><?= $it['jumlah_terima'] ?></td>
                        <td class="text-center"><?= $it['sudah_diretur'] ?></td>
                        <td class="text-center fw-bold"><?= $sisa ?></td>
                        <td>
                            <input type="number" name="jumlah[<?= $it['iddetail_penerimaan'] ?>]" class="form-control form-control-sm text-end" min="0" max="<?= $sisa ?>" value="0" <?= $sisa == 0 ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <input type="text" name="alasan[<?= $it['iddetail_penerimaan'] ?>]" class="form-control form-control-sm" placeholder="Contoh: Kemasan rusak / expired" <?= $sisa == 0 ? 'readonly' : '' ?>>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan retur</button>
    <a href="tambah.php" class="btn btn-secondary">Kembali</a>
</form>

<?php endif; ?>
<?php require_once '../includes/footer.php'; ?>
