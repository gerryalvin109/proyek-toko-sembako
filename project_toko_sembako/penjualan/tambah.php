<?php
$page_title  = "Tambah Penjualan";
$active_menu = "penjualan";
require_once '../includes/header.php';

$error = "";

// Ambil margin aktif
$q_margin = mysqli_query($conn, "SELECT idmargin_penjualan, persen FROM margin_penjualan WHERE status = 1 LIMIT 1");
$margin   = $q_margin ? mysqli_fetch_assoc($q_margin) : null;

if (!$margin) {
    echo "<div class='alert alert-warning'>Belum ada Margin Penjualan yang aktif. Aktifkan terlebih dahulu di menu Margin Penjualan.</div>";
    require_once '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iduser     = (int)$_SESSION['iduser'];
    $idmargin   = (int)$margin['idmargin_penjualan'];
    $idbarang_a = $_POST['idbarang'] ?? [];
    $harga_a    = $_POST['harga_satuan'] ?? [];
    $jumlah_a   = $_POST['jumlah'] ?? [];

    $items = [];
    for ($i = 0; $i < count($idbarang_a); $i++) {
        $idb = (int)$idbarang_a[$i];
        $hrg = (int)$harga_a[$i];
        $jml = (int)$jumlah_a[$i];
        if ($idb > 0 && $jml > 0) {
            if (isset($items[$idb])) {
                $items[$idb]['jumlah'] += $jml;
                $items[$idb]['sub']     = $items[$idb]['harga'] * $items[$idb]['jumlah'];
            } else {
                $items[$idb] = [
                    'idbarang' => $idb,
                    'harga'    => $hrg,
                    'jumlah'   => $jml,
                    'sub'      => $hrg * $jml
                ];
            }
        }
    }

    if (count($items) === 0) {
        $error = "Pilih minimal 1 barang dengan jumlah lebih dari 0.";
    } else {
        // Validasi stok via fn_cek_stok_barang
        foreach ($items as $idb => $it) {
            $q_stok = mysqli_query($conn, "SELECT nama, fn_cek_stok_barang($idb) AS stok FROM barang WHERE idbarang = $idb");
            $s_row  = mysqli_fetch_assoc($q_stok);
            if ($it['jumlah'] > (int)$s_row['stok']) {
                $error = "Stok {$s_row['nama']} tidak mencukupi (tersedia: {$s_row['stok']}, diminta: {$it['jumlah']}).";
                break;
            }
        }

        if ($error === "") {
            $subtotal_nilai = 0;
            foreach ($items as $it) {
                $subtotal_nilai += $it['sub'];
            }

            // Hitung PPN menggunakan Function database fn_hitung_ppn
            $q_ppn       = mysqli_query($conn, "SELECT fn_hitung_ppn($subtotal_nilai) AS ppn");
            $ppn         = (int)mysqli_fetch_assoc($q_ppn)['ppn'];
            $total_nilai = $subtotal_nilai + $ppn;

            mysqli_begin_transaction($conn);
            try {
                mysqli_query($conn, "INSERT INTO penjualan (subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan) VALUES ($subtotal_nilai, $ppn, $total_nilai, $iduser, $idmargin)");
                $idpenjualan = mysqli_insert_id($conn);

                foreach ($items as $it) {
                    // INSERT ini otomatis memicu trg_after_insert_detail_penjualan (stok berkurang di kartu_stok)
                    mysqli_query($conn, "INSERT INTO detail_penjualan (harga_satuan, jumlah, subtotal, penjualan_idpenjualan, idbarang) VALUES ({$it['harga']}, {$it['jumlah']}, {$it['sub']}, $idpenjualan, {$it['idbarang']})");
                }

                mysqli_commit($conn);
                header("Location: detail.php?id=$idpenjualan");
                exit;
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = "Gagal menyimpan penjualan: " . $e->getMessage();
            }
        }
    }
}

// Ambil barang aktif + harga jual + stok tersedia
$q_brg = mysqli_query($conn, "
    SELECT id_barang, nama_barang, nama_satuan, harga_jual, fn_cek_stok_barang(id_barang) AS stok
    FROM v_harga_jual_barang
    ORDER BY nama_barang ASC
");
$barang_list = [];
if ($q_brg) {
    while ($b = mysqli_fetch_assoc($q_brg)) {
        $barang_list[] = $b;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Tambah Penjualan</h4>
        <p class="text-muted small mb-0">Kasir: <b><?= htmlspecialchars($_SESSION['username']) ?></b> | Margin Aktif: <span class="badge bg-info text-dark"><?= $margin['persen'] ?>%</span></p>
    </div>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger py-2"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="fw-bold mb-0">Daftar Belanja</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahBaris()"><i class="bi bi-plus-lg me-1"></i>Tambah Baris</button>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barang (Stok Tersedia)</th>
                        <th style="width: 170px;">Harga Jual (Rp)</th>
                        <th style="width: 120px;">Jumlah</th>
                        <th style="width: 180px;" class="text-end">Subtotal</th>
                        <th style="width: 60px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodyItem">
                    <tr class="baris-item">
                        <td>
                            <select name="idbarang[]" class="form-select form-select-sm select-barang" onchange="isiHarga(this)" required>
                                <option value="" data-harga="0" data-stok="0">-- Pilih Barang --</option>
                                <?php foreach ($barang_list as $b): ?>
                                    <option value="<?= $b['id_barang'] ?>" data-harga="<?= $b['harga_jual'] ?>" data-stok="<?= $b['stok'] ?>" <?= $b['stok'] <= 0 ? 'disabled' : '' ?>>
                                        <?= htmlspecialchars($b['nama_barang']) ?> (<?= htmlspecialchars($b['nama_satuan']) ?>) — Stok: <?= $b['stok'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="harga_satuan[]" class="form-control form-control-sm text-end bg-light input-harga" value="0" readonly></td>
                        <td><input type="number" name="jumlah[]" class="form-control form-control-sm text-end input-jumlah" min="1" value="1" oninput="hitungTotal()" required></td>
                        <td class="text-end fw-semibold teks-subtotal">Rp 0</td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)"><i class="bi bi-trash"></i></button></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-semibold">Subtotal</td>
                        <td class="text-end fw-semibold" id="lblSubtotal">Rp 0</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-semibold">PPN (11%)</td>
                        <td class="text-end fw-semibold" id="lblPpn">Rp 0</td>
                        <td></td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold">Total Bayar</td>
                        <td class="text-end fw-bold text-success" id="lblTotal">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary fw-semibold">Simpan Penjualan</button>
        <a href="index.php" class="btn btn-light border">Batal</a>
    </div>
</form>

<script>
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function isiHarga(selectEl) {
    const opt   = selectEl.options[selectEl.selectedIndex];
    const harga = opt.getAttribute('data-harga') || 0;
    const stok  = opt.getAttribute('data-stok') || 1;
    const row   = selectEl.closest('tr');
    row.querySelector('.input-harga').value = harga;
    row.querySelector('.input-jumlah').max  = stok;
    hitungTotal();
}

function hitungTotal() {
    let subtotal = 0;
    document.querySelectorAll('#tbodyItem tr.baris-item').forEach(function(row) {
        const hrg = parseInt(row.querySelector('.input-harga').value) || 0;
        const jml = parseInt(row.querySelector('.input-jumlah').value) || 0;
        const sub = hrg * jml;
        subtotal += sub;
        row.querySelector('.teks-subtotal').textContent = formatRupiah(sub);
    });
    const ppn   = Math.round(subtotal * 0.11);
    const total = subtotal + ppn;
    document.getElementById('lblSubtotal').textContent = formatRupiah(subtotal);
    document.getElementById('lblPpn').textContent      = formatRupiah(ppn);
    document.getElementById('lblTotal').textContent    = formatRupiah(total);
}

function tambahBaris() {
    const tbody = document.getElementById('tbodyItem');
    const first = tbody.querySelector('tr.baris-item');
    const clone = first.cloneNode(true);
    clone.querySelector('.select-barang').selectedIndex = 0;
    clone.querySelector('.input-harga').value = 0;
    clone.querySelector('.input-jumlah').value = 1;
    clone.querySelector('.teks-subtotal').textContent = 'Rp 0';
    tbody.appendChild(clone);
}

function hapusBaris(btn) {
    const tbody = document.getElementById('tbodyItem');
    if (tbody.querySelectorAll('tr.baris-item').length > 1) {
        btn.closest('tr').remove();
        hitungTotal();
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>