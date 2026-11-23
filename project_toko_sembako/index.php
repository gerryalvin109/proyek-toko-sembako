<?php
$page_title  = "Beranda";
$active_menu = "dashboard";
require_once 'includes/header.php';

// Hanya Admin yang boleh mengubah margin aktif
if (isset($_GET['set_margin']) && $is_admin) {
    $id_m = (int)$_GET['set_margin'];
    mysqli_query($conn, "UPDATE margin_penjualan SET status = CASE WHEN idmargin_penjualan = $id_m THEN 1 ELSE 0 END");
    header("Location: index.php");
    exit;
}

function hitung_data($conn, $tabel) {
    $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `$tabel`");
    if ($q) {
        $r = mysqli_fetch_assoc($q);
        return (int)$r['total'];
    }
    return 0;
}

// Susun kartu ringkasan sesuai hak akses role di sidebar
$cards = [];

if ($is_admin || $is_gudang) {
    $cards[] = ['judul' => 'Barang',     'ket' => hitung_data($conn, 'barang') . ' data',     'link' => 'barang/index.php'];
    $cards[] = ['judul' => 'Satuan',     'ket' => hitung_data($conn, 'satuan') . ' data',     'link' => 'satuan/index.php'];
    $cards[] = ['judul' => 'Vendor',     'ket' => hitung_data($conn, 'vendor') . ' data',     'link' => 'vendor/index.php'];
}

if ($is_admin) {
    $cards[] = ['judul' => 'Margin',     'ket' => hitung_data($conn, 'margin_penjualan') . ' data', 'link' => 'margin/index.php'];
    $cards[] = ['judul' => 'User',       'ket' => hitung_data($conn, 'user') . ' data',             'link' => 'user/index.php'];
    $cards[] = ['judul' => 'Role',       'ket' => hitung_data($conn, 'role') . ' data',             'link' => 'role/index.php'];
}

if ($is_admin || $is_gudang) {
    $cards[] = ['judul' => 'Pengadaan',  'ket' => hitung_data($conn, 'pengadaan') . ' data',  'link' => 'pengadaan/index.php'];
    $cards[] = ['judul' => 'Penerimaan', 'ket' => hitung_data($conn, 'penerimaan') . ' data', 'link' => 'penerimaan/index.php'];
    $cards[] = ['judul' => 'Retur',      'ket' => hitung_data($conn, 'retur') . ' data',      'link' => 'retur/index.php'];
}

if ($is_admin || $is_kasir) {
    $cards[] = ['judul' => 'Penjualan',  'ket' => hitung_data($conn, 'penjualan') . ' data',  'link' => 'penjualan/index.php'];
}

// Semua role bisa melihat Kartu Stok
$cards[] = ['judul' => 'Stok', 'ket' => 'laporan & kartu stok', 'link' => 'stok/index.php'];

$q_margin  = mysqli_query($conn, "SELECT idmargin_penjualan AS id_margin, persen, status FROM margin_penjualan ORDER BY persen ASC");
$q_katalog = mysqli_query($conn, "SELECT * FROM v_harga_jual_barang ORDER BY id_barang ASC");
?>

<div class="mb-4">
    <h4 class="fw-bold mb-0">Beranda</h4>
    <p class="text-muted mb-0">Selamat datang, <b><?= htmlspecialchars($_SESSION['username']) ?></b> (<?= htmlspecialchars($_SESSION['nama_role']) ?>)</p>
</div>

<!-- Grid Kartu Ringkasan (Dinamis sesuai Role) -->
<div class="row g-3 mb-4">
    <?php foreach ($cards as $c): ?>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="<?= BASE_URL ?>/<?= $c['link'] ?>" class="text-decoration-none text-dark">
            <div class="card h-100 border">
                <div class="card-body py-3">
                    <div class="fw-bold"><?= $c['judul'] ?></div>
                    <div class="small text-muted mt-1"><?= $c['ket'] ?></div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Simulasi Margin (Khusus Admin) -->
<?php if ($is_admin): ?>
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-percent me-2 text-primary"></i>Pilihan Margin Aktif</h6>
    </div>
    <div class="card-body d-flex flex-wrap gap-2">
        <?php if ($q_margin): while ($m = mysqli_fetch_assoc($q_margin)): ?>
            <a href="index.php?set_margin=<?= $m['id_margin'] ?>" class="btn <?= $m['status'] == 1 ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>">
                Margin <?= $m['persen'] ?>% <?= $m['status'] == 1 ? '(Aktif)' : '' ?>
            </a>
        <?php endwhile; endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Katalog Harga Jual (Admin & Kasir) -->
<?php if ($is_admin || $is_kasir): ?>
<div class="card">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-tags me-2 text-success"></i>Katalog Harga Jual Barang</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga Pokok</th>
                    <th>Margin Aktif</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($q_katalog && mysqli_num_rows($q_katalog) > 0): while ($k = mysqli_fetch_assoc($q_katalog)): ?>
                <tr>
                    <td><?= $k['id_barang'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($k['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($k['nama_satuan']) ?></td>
                    <td>Rp <?= number_format($k['harga_pokok'], 0, ',', '.') ?></td>
                    <td><span class="badge bg-info text-dark"><?= $k['persen_margin'] ?>%</span></td>
                    <td class="fw-bold text-success">Rp <?= number_format($k['harga_jual'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data katalog barang aktif.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>