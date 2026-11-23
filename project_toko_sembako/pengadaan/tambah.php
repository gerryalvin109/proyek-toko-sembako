<?php
$page_title  = "Tambah Pengadaan";
$active_menu = "pengadaan";
require_once '../includes/header.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idvendor   = (int)($_POST['idvendor'] ?? 0);
    $iduser     = (int)$_SESSION['iduser'];
    $idbarang_a = $_POST['idbarang'] ?? [];
    $harga_a    = $_POST['harga_satuan'] ?? [];
    $jumlah_a   = $_POST['jumlah'] ?? [];

    $items = [];
    $subtotal_nilai = 0;

    for ($i = 0; $i < count($idbarang_a); $i++) {
        $idb = (int)$idbarang_a[$i];
        $hrg = (int)$harga_a[$i];
        $jml = (int)$jumlah_a[$i];
        if ($idb > 0 && $jml > 0 && $hrg >= 0) {
            $sub = $hrg * $jml;
            $subtotal_nilai += $sub;
            $items[] = [
                'idbarang' => $idb,
                'harga'    => $hrg,
                'jumlah'   => $jml,
                'sub'      => $sub
            ];
        }
    }

    if ($idvendor <= 0 || count($items) === 0) {
        $error = "Pilih vendor dan masukkan minimal 1 barang dengan jumlah lebih dari 0.";
    } else {
        $ppn         = (int)round($subtotal_nilai * 0.11);
        $total_nilai = $subtotal_nilai + $ppn;

        mysqli_begin_transaction($conn);
        try {
            // Status 'D' = Belum diterima sepenuhnya (menunggu penerimaan)
            mysqli_query($conn, "INSERT INTO pengadaan (user_iduser, status, vendor_idvendor, subtotal_nilai, ppn, total_nilai) VALUES ($iduser, 'D', $idvendor, $subtotal_nilai, $ppn, $total_nilai)");
            $idpengadaan = mysqli_insert_id($conn);

            foreach ($items as $it) {
                mysqli_query($conn, "INSERT INTO detail_pengadaan (harga_satuan, jumlah, sub_total, idbarang, idpengadaan) VALUES ({$it['harga']}, {$it['jumlah']}, {$it['sub']}, {$it['idbarang']}, $idpengadaan)");
            }

            mysqli_commit($conn);
            header("Location: detail.php?id=$idpengadaan&msg=created");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Gagal membuat pengadaan: " . $e->getMessage();
        }
    }
}

$vendors = mysqli_query($conn, "SELECT * FROM v_vendor_aktif ORDER BY nama_vendor ASC");
$q_brg   = mysqli_query($conn, "SELECT id_barang, nama_barang, nama_satuan, harga_pokok FROM v_barang_aktif ORDER BY nama_barang ASC");
$barang_list = [];
if ($q_brg) {
    while ($b = mysqli_fetch_assoc($q_brg)) {
        $barang_list[] = $b;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Tambah Pengadaan</h4>
        <p class="text-muted small mb-0">Buat pesanan barang baru ke vendor (selanjutnya diterima di menu Penerimaan)</p>
    </div>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger py-2"><?= $error ?></div>
<?php endif; ?>

<form method="POST" id="formPengadaan">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Vendor</label>
                    <select name="idvendor" class="form-select" required>
                        <option value="">-- Pilih Vendor --</option>
                        <?php if ($vendors): while ($v = mysqli_fetch_assoc($vendors)): ?>
                            <option value="<?= $v['id_vendor'] ?>"><?= htmlspecialchars($v['nama_vendor']) ?></option>
                        <?php endwhile; endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Petugas</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($_SESSION['username']) ?>" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="fw-bold mb-0">Daftar Barang Dipesan</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahBaris()"><i class="bi bi-plus-lg me-1"></i>Tambah Baris</button>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0" id="tabelItem">
                <thead class="table-dark">
                    <tr>
                        <th>Barang</th>
                        <th style="width: 180px;">Harga Satuan (Rp)</th>
                        <th style="width: 120px;">Jumlah</th>
                        <th style="width: 180px;" class="text-end">Subtotal</th>
                        <th style="width: 60px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodyItem">
                    <tr class="baris-item">
                        <td>
                            <select name="idbarang[]" class="form-select form-select-sm select-barang" onchange="isiHarga(this)" required>
                                <option value="" data-harga="0">-- Pilih Barang --</option>
                                <?php foreach ($barang_list as $b): ?>
                                    <option value="<?= $b['id_barang'] ?>" data-harga="<?= $b['harga_pokok'] ?>">
                                        <?= htmlspecialchars($b['nama_barang']) ?> (<?= htmlspecialchars($b['nama_satuan']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="harga_satuan[]" class="form-control form-control-sm text-end input-harga" min="0" value="0" oninput="hitungTotal()" required></td>
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
                        <td colspan="3" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold text-primary" id="lblTotal">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary fw-semibold">Simpan Pengadaan</button>
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
    const row   = selectEl.closest('tr');
    row.querySelector('.input-harga').value = harga;
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
