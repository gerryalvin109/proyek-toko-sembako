<?php
require_once 'koneksi.php';

$pesan = "";
if (isset($_POST['ubah_margin'])) {
    $id_pilihan = (int)$_POST['id_margin'];
    $sql_update = "UPDATE margin_penjualan 
                   SET status = CASE WHEN idmargin_penjualan = $id_pilihan THEN 1 ELSE 0 END,
                       updated_at = CASE WHEN idmargin_penjualan = $id_pilihan THEN CURRENT_TIMESTAMP ELSE updated_at END
                   WHERE idmargin_penjualan > 0";
    if (mysqli_query($conn, $sql_update)) {
        $pesan = "Margin penjualan aktif berhasil diperbarui!";
    }
}

render_header("Dashboard Simulasi Margin");
render_sidebar("dashboard");
?>

<div class="col-md-9 col-lg-10 p-4">
    <?php if ($pesan != ""): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= $pesan ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="mb-4">
        <h4 class="fw-bold mb-0">Dashboard Simulasi Margin & Katalog Harga Barang</h4>
        <p class="text-muted mb-0">Pilih salah satu margin penjualan di bawah ini untuk mengubah harga jual secara otomatis</p>
    </div>

    <div class="card mb-4 border-start border-primary border-4">
        <div class="card-body">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-sliders me-2"></i>Pilih Margin Penjualan Aktif</h6>
            <form method="POST" class="d-flex gap-2 flex-wrap">
                <input type="hidden" name="ubah_margin" value="1">
                <?php
                $q_m = mysqli_query($conn, "SELECT * FROM v_margin_all ORDER BY persentase_margin ASC");
                while ($m = mysqli_fetch_assoc($q_m)):
                    $is_active = ($m['status'] == 1);
                ?>
                    <button type="submit" name="id_margin" value="<?= $m['id_margin'] ?>" 
                            class="btn <?= $is_active ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?> px-4 py-2">
                        Margin <?= $m['tampilan_margin'] ?>
                        <?php if ($is_active): ?>
                            <span class="badge bg-light text-primary ms-2">AKTIF</span>
                        <?php endif; ?>
                    </button>
                <?php endwhile; ?>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Katalog Harga Jual Barang Aktif</h6>
            <span class="badge bg-info text-dark">Otomatis mengikuti Margin Aktif</span>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori / Jenis</th>
                        <th>Satuan</th>
                        <th>Harga Pokok</th>
                        <th>Margin Aktif</th>
                        <th>Harga Jual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_katalog = mysqli_query($conn, "SELECT * FROM v_harga_jual_barang ORDER BY id_barang ASC");
                    while ($row = mysqli_fetch_assoc($q_katalog)):
                    ?>
                    <tr>
                        <td><?= $row['id_barang'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= $row['nama_jenis'] ?></td>
                        <td><?= htmlspecialchars($row['nama_satuan']) ?></td>
                        <td>Rp <?= number_format($row['harga_pokok'], 0, ',', '.') ?></td>
                        <td><span class="badge bg-primary"><?= $row['margin_aktif'] ?></span></td>
                        <td class="fw-bold text-success">Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php render_footer(); ?>