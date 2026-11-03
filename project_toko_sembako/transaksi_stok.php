<?php
require_once 'koneksi.php';

$pesan = ""; $error = "";

// EKSEKUSI STORED PROCEDURE SAAT FORM DISUBMIT
if (isset($_POST['proses_terima'])) {
    $idvendor = (int)$_POST['idvendor'];
    $iduser   = (int)$_POST['iduser'];
    $idbarang = (int)$_POST['idbarang'];
    $jumlah   = (int)$_POST['jumlah'];

    if ($jumlah > 0) {
        // Memanggil Stored Procedure sp_catat_penerimaan_barang
        $sql_sp = "CALL sp_catat_penerimaan_barang($idvendor, $iduser, $idbarang, $jumlah)";
        if (mysqli_query($conn, $sql_sp)) {
            // Bersihkan sisa result set dari CALL procedure agar query berikutnya lancar
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) {;}
            $pesan = "Transaksi berhasil! Stored Procedure dijalankan & Trigger otomatis memperbarui Kartu Stok.";
        } else {
            $error = "Gagal menjalankan Stored Procedure: " . mysqli_error($conn);
        }
    } else {
        $error = "Jumlah barang masuk harus lebih dari 0.";
    }
}

// Ambil data untuk dropdown form (menggunakan View Aktif)
$list_vendor = [];
$q_v = mysqli_query($conn, "SELECT * FROM v_vendor_aktif");
while ($v = mysqli_fetch_assoc($q_v)) { $list_vendor[] = $v; }

$list_user = [];
$q_u = mysqli_query($conn, "SELECT * FROM v_user");
while ($u = mysqli_fetch_assoc($q_u)) { $list_user[] = $u; }

$list_barang = [];
$q_b = mysqli_query($conn, "SELECT * FROM v_barang_aktif");
while ($b = mysqli_fetch_assoc($q_b)) { $list_barang[] = $b; }

render_header("Transaksi & Kartu Stok");
render_sidebar("stok");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i><?= $pesan ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="mb-4">
        <h4 class="fw-bold mb-0">Integrasi Stored Procedure, Function & Trigger Kartu Stok</h4>
        <p class="text-muted mb-0">Simulasi penerimaan barang masuk yang otomatis memperbarui sisa stok melalui Trigger</p>
    </div>

    <div class="row g-4">
        <!-- BAGIAN 1: FORM INPUT (MEMANGGIL STORED PROCEDURE) -->
        <div class="col-lg-5">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-primary mb-0"><i class="bi bi-box-arrow-in-down me-2"></i>1. Input Barang Masuk (Stored Procedure)</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="proses_terima" value="1">
                        <div class="mb-3">
                            <label class="form-label">Pilih Vendor Aktif</label>
                            <select name="idvendor" class="form-select" required>
                                <?php foreach ($list_vendor as $v): ?>
                                    <option value="<?= $v['id_vendor'] ?>"><?= htmlspecialchars($v['nama_vendor']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Petugas Penerima (User)</label>
                            <select name="iduser" class="form-select" required>
                                <?php foreach ($list_user as $u): ?>
                                    <option value="<?= $u['id_user'] ?>"><?= htmlspecialchars($u['username']) ?> (<?= $u['nama_role'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Barang Aktif</label>
                            <select name="idbarang" class="form-select" required>
                                <?php foreach ($list_barang as $b): ?>
                                    <option value="<?= $b['id_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?> - Rp <?= number_format($b['harga_pokok'], 0, ',', '.') ?>/<?= $b['nama_satuan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Barang Masuk</label>
                            <input type="number" name="jumlah" class="form-control" min="1" value="10" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-play-circle me-1"></i>Proses Penerimaan Barang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: TABEL MONITORING STOK (MEMANGGIL FUNCTION) -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-calculator me-2 text-success"></i>2. Posisi Stok Barang Saat Ini (Function)</h6>
                    <span class="badge bg-success">fn_cek_stok_barang()</span>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Harga Pokok</th>
                                <th class="text-center">Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Memanggil Function fn_cek_stok_barang langsung di dalam query SELECT
                            $q_stok = mysqli_query($conn, "SELECT id_barang, nama_barang, nama_satuan, harga_pokok, fn_cek_stok_barang(id_barang) AS stok_akhir FROM v_barang_aktif ORDER BY id_barang ASC");
                            if ($q_stok):
                                while ($s = mysqli_fetch_assoc($q_stok)):
                            ?>
                            <tr>
                                <td><?= $s['id_barang'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($s['nama_barang']) ?></td>
                                <td><?= htmlspecialchars($s['nama_satuan']) ?></td>
                                <td>Rp <?= number_format($s['harga_pokok'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $s['stok_akhir'] > 0 ? 'bg-primary' : 'bg-secondary' ?> fs-6">
                                        <?= $s['stok_akhir'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: RIWAYAT KARTU STOK (HASIL OTOMATIS DARI TRIGGER) -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>3. Log Riwayat Tabel Kartu Stok (Otomatis Terisi Oleh Trigger)</h6>
                    <span class="badge bg-warning text-dark">trg_after_insert_detail_penerimaan</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Kartu Stok</th>
                                <th>Waktu Transaksi</th>
                                <th>Nama Barang</th>
                                <th>Jenis Transaksi</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Sisa Stok (Stock)</th>
                                <th>ID Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $q_ks = mysqli_query($conn, "SELECT k.*, b.nama AS nama_barang 
                                                         FROM kartu_stok k 
                                                         JOIN barang b ON k.idbarang = b.idbarang 
                                                         ORDER BY k.idkartu_stok DESC LIMIT 15");
                            if ($q_ks && mysqli_num_rows($q_ks) > 0):
                                while ($k = mysqli_fetch_assoc($q_ks)):
                            ?>
                            <tr>
                                <td>#<?= $k['idkartu_stok'] ?></td>
                                <td><?= $k['created_at'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($k['nama_barang']) ?></td>
                                <td>
                                    <?php if ($k['jenis_transaksi'] == 'M'): ?>
                                        <span class="badge bg-success">M - Barang Masuk</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">K - Barang Keluar</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-success fw-bold">+<?= $k['masuk'] ?></td>
                                <td class="text-danger fw-bold">-<?= $k['keluar'] ?></td>
                                <td class="fw-bold bg-light"><?= $k['stock'] ?></td>
                                <td>ID Penerimaan #<?= $k['idtransaksi'] ?></td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Belum ada riwayat transaksi di Kartu Stok. Silakan input barang masuk pada form di atas.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php render_footer(); ?>