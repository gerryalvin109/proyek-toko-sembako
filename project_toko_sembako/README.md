# Sistem Informasi Manajemen Toko Sembako (`uts_pbd`)

Aplikasi web berbasis **PHP Native**, **Bootstrap 5**, dan **MySQL** untuk mengelola operasional inventaris, pengadaan, penerimaan parsial, retur vendor, hingga penjualan kasir dengan pencatatan kartu stok otomatis berbasis **Database Trigger, Function, View, dan Stored Procedure**.

---

## Fitur Utama

### 1. Autentikasi & Hak Akses Berbasis Role (RBAC)
* Login & logout aman menggunakan sesi PHP dan hashing password **`SHA2-256`**.
* **Admin:** Akses penuh ke seluruh data master, manajemen user & role, pengaturan margin penjualan aktif secara real-time, seluruh transaksi, serta laporan kartu stok.
* **Staff Gudang:** Mengelola data master barang/satuan/vendor, membuat pengadaan, memproses penerimaan barang (parsial maupun penuh), retur barang ke vendor, serta memantau kartu stok.
* **Kasir:** Melihat katalog harga jual aktif, memproses transaksi penjualan dengan validasi stok real-time, serta memantau kartu stok.

### 2. Otomatisasi Database (`uts_pbd`)
* **15 Tabel Relasional:** 6 tabel master (`role`, `user`, `vendor`, `satuan`, `barang`, `margin_penjualan`) dan 9 tabel transaksi (`pengadaan`, `detail_pengadaan`, `penerimaan`, `detail_penerimaan`, `retur`, `detail_retur`, `penjualan`, `detail_penjualan`, `kartu_stok`).
* **Database Views:** View data master (`_all` & `_aktif`), katalog harga jual (`v_harga_jual_barang`), serta 9 View transaksi lengkap.
* **Database Functions:**
  * `fn_cek_stok_barang(id_barang)` — Mengecek saldo stok akhir barang secara real-time.
  * `fn_hitung_ppn(subtotal)` — Menghitung PPN 11% secara konsisten.
  * `fn_hitung_harga_jual(harga_pokok, persen)` — Menghitung harga jual berdasarkan margin aktif.
* **Database Triggers:**
  * `trg_after_insert_detail_penerimaan` — Otomatis mencatat mutasi stok masuk (`M`) di `kartu_stok`.
  * `trg_after_insert_detail_retur` — Otomatis mencatat mutasi stok keluar (`K`) saat retur ke vendor.
  * `trg_after_insert_detail_penjualan` — Otomatis mencatat mutasi stok keluar (`K`) saat transaksi penjualan.
* **Stored Procedure:**
  * `sp_update_status_pengadaan(idpengadaan)` — Memperbarui status pengadaan dan penerimaan secara otomatis antara **`D` (Diterima/Parsial)** dan **`S` (Selesai)** berdasarkan akumulasi barang yang diterima.

---

## Cara Menjalankan di Laragon

1. Letakkan folder `project_toko_sembako` di dalam direktori `www` Laragon (misalnya `C:\laragon\www\` atau `D:\laragon\www\`).
2. Jalankan **Apache** dan **MySQL** melalui Laragon.
3. Buka **HeidiSQL**, buat atau pilih database `uts_pbd`, lalu import file **`uts_pbd.sql`** yang tersedia di root folder proyek ini (sudah mencakup seluruh struktur tabel, data awal, View, Function, Trigger, dan Stored Procedure).
4. Sesuaikan konfigurasi koneksi database pada `config/database.php` jika diperlukan (default: host `localhost`, user `root`, password kosong, database `uts_pbd`).
5. Buka browser dan akses:
   ```text
   http://localhost/project_toko_sembako/login.php