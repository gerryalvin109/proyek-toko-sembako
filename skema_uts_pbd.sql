CREATE DATABASE uts_pbd;
USE uts_pbd;

CREATE TABLE satuan (
  idsatuan INT NOT NULL,
  nama_satuan VARCHAR(45) NOT NULL,
  status TINYINT
);
ALTER TABLE satuan
ADD PRIMARY KEY (idsatuan);

CREATE TABLE role (
  idrole INT NOT NULL,
  nama_role VARCHAR(100) NOT NULL
);
ALTER TABLE role
ADD PRIMARY KEY (idrole);

CREATE TABLE vendor (
  idvendor INT NOT NULL,
  nama_vendor VARCHAR(100) NOT NULL,
  badan_hukum CHAR(1),
  status CHAR(1)
);
ALTER TABLE vendor
ADD PRIMARY KEY (idvendor);
SELECT * FROM vendor;

CREATE TABLE `user` (
  iduser INT NOT NULL,
  username VARCHAR(45) NOT NULL,
  password VARCHAR(100) NOT NULL,
  idrole INT NOT NULL
);
ALTER TABLE `user`
ADD PRIMARY KEY (iduser);
ALTER TABLE `user`
ADD CONSTRAINT fk_user_role
FOREIGN KEY (idrole)
REFERENCES role(idrole);

CREATE TABLE barang (
  idbarang INT NOT NULL,
  jenis CHAR(1),
  nama VARCHAR(45) NOT NULL,
  idsatuan INT NOT NULL,
  status TINYINT,
  harga INT
);
ALTER TABLE barang
ADD PRIMARY KEY (idbarang);
ALTER TABLE barang
ADD CONSTRAINT fk_barang_satuan
FOREIGN KEY (idsatuan)
REFERENCES satuan(idsatuan);
SELECT * FROM barang;

-- 6. MARGIN PENJUALAN
CREATE TABLE margin_penjualan (
  idmargin_penjualan INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  persen DOUBLE,
  status TINYINT,
  iduser INT NOT NULL,
  updated_at TIMESTAMP NULL
);
ALTER TABLE margin_penjualan
ADD PRIMARY KEY (idmargin_penjualan);
ALTER TABLE margin_penjualan
ADD CONSTRAINT fk_margin_user
FOREIGN KEY (iduser)
REFERENCES `user`(iduser);
SELECT * FROM margin_penjualan;
-- 7. PENGADAAN
CREATE TABLE pengadaan (
  idpengadaan BIGINT NOT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_iduser INT NOT NULL,
  status CHAR(1),
  vendor_idvendor INT NOT NULL,
  subtotal_nilai INT,
  ppn INT,
  total_nilai INT
);
ALTER TABLE pengadaan
ADD PRIMARY KEY (idpengadaan);
ALTER TABLE pengadaan
ADD CONSTRAINT fk_pengadaan_user
FOREIGN KEY (user_iduser)
REFERENCES `user`(iduser);
ALTER TABLE pengadaan
ADD CONSTRAINT fk_pengadaan_vendor
FOREIGN KEY (vendor_idvendor)
REFERENCES vendor(idvendor);
SELECT * FROM pengadaan;
-- 8. DETAIL PENGADAAN
CREATE TABLE detail_pengadaan (
  iddetail_pengadaan BIGINT NOT NULL,
  harga_satuan INT,
  jumlah INT,
  sub_total INT,
  idbarang INT NOT NULL,
  idpengadaan BIGINT NOT NULL
);
ALTER TABLE detail_pengadaan
ADD PRIMARY KEY (iddetail_pengadaan);
ALTER TABLE detail_pengadaan
ADD CONSTRAINT fk_detpengadaan_barang
FOREIGN KEY (idbarang)
REFERENCES barang(idbarang);
ALTER TABLE detail_pengadaan
ADD CONSTRAINT fk_detpengadaan_pengadaan
FOREIGN KEY (idpengadaan)
REFERENCES pengadaan(idpengadaan);

-- 9. PENERIMAAN
CREATE TABLE penerimaan (
  idpenerimaan BIGINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status CHAR(1),
  idpengadaan BIGINT NOT NULL,
  iduser INT NOT NULL
);
ALTER TABLE penerimaan
ADD PRIMARY KEY (idpenerimaan);
ALTER TABLE penerimaan
ADD CONSTRAINT fk_penerimaan_pengadaan
FOREIGN KEY (idpengadaan)
REFERENCES pengadaan(idpengadaan);
ALTER TABLE penerimaan
ADD CONSTRAINT fk_penerimaan_user
FOREIGN KEY (iduser)
REFERENCES `user`(iduser);

-- 10. DETAIL PENERIMAAN
CREATE TABLE detail_penerimaan (
  iddetail_penerimaan BIGINT NOT NULL,
  idpenerimaan BIGINT NOT NULL,
  barang_idbarang INT NOT NULL,
  jumlah_terima INT,
  harga_satuan_terima INT,
  sub_total_terima INT
);
ALTER TABLE detail_penerimaan
ADD PRIMARY KEY (iddetail_penerimaan);
ALTER TABLE detail_penerimaan
ADD CONSTRAINT fk_detpenerimaan_penerimaan
FOREIGN KEY (idpenerimaan)
REFERENCES penerimaan(idpenerimaan);
ALTER TABLE detail_penerimaan
ADD CONSTRAINT fk_detpenerimaan_barang
FOREIGN KEY (barang_idbarang)
REFERENCES barang(idbarang);

-- 11. PENJUALAN
CREATE TABLE penjualan (
  idpenjualan INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  subtotal_nilai INT,
  ppn INT,
  total_nilai INT,
  iduser INT NOT NULL,
  idmargin_penjualan INT NOT NULL
);
ALTER TABLE penjualan
ADD PRIMARY KEY (idpenjualan);
ALTER TABLE penjualan
ADD CONSTRAINT fk_penjualan_user
FOREIGN KEY (iduser)
REFERENCES `user`(iduser);
ALTER TABLE penjualan
ADD CONSTRAINT fk_penjualan_margin
FOREIGN KEY (idmargin_penjualan)
REFERENCES margin_penjualan(idmargin_penjualan);

-- 12. DETAIL PENJUALAN
CREATE TABLE detail_penjualan (
  iddetail_penjualan BIGINT NOT NULL,
  harga_satuan INT,
  jumlah INT,
  subtotal INT,
  penjualan_idpenjualan INT NOT NULL,
  idbarang INT NOT NULL
);
ALTER TABLE detail_penjualan
ADD PRIMARY KEY (iddetail_penjualan);
ALTER TABLE detail_penjualan
ADD CONSTRAINT fk_detpenjualan_penjualan
FOREIGN KEY (penjualan_idpenjualan)
REFERENCES penjualan(idpenjualan);
ALTER TABLE detail_penjualan
ADD CONSTRAINT fk_detpenjualan_barang
FOREIGN KEY (idbarang)
REFERENCES barang(idbarang);

-- 13. KARTU STOK
CREATE TABLE kartu_stok (
  idkartu_stok BIGINT NOT NULL,
  jenis_transaksi CHAR(1),
  masuk INT,
  keluar INT,
  stock INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  idtransaksi INT,
  idbarang INT NOT NULL
);
ALTER TABLE kartu_stok
ADD PRIMARY KEY (idkartu_stok);
ALTER TABLE kartu_stok
ADD CONSTRAINT fk_kartustok_barang
FOREIGN KEY (idbarang)
REFERENCES barang(idbarang);

-- 14. RETUR
CREATE TABLE retur (
  idretur BIGINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  idpenerimaan BIGINT NOT NULL,
  iduser INT NOT NULL
);
ALTER TABLE retur
ADD PRIMARY KEY (idretur);
ALTER TABLE retur
ADD CONSTRAINT fk_retur_penerimaan
FOREIGN KEY (idpenerimaan)
REFERENCES penerimaan(idpenerimaan);
ALTER TABLE retur
ADD CONSTRAINT fk_retur_user
FOREIGN KEY (iduser)
REFERENCES `user`(iduser);

-- 15. DETAIL RETUR
CREATE TABLE detail_retur (
  iddetail_retur INT NOT NULL,
  jumlah INT,
  alasan VARCHAR(200),
  idretur BIGINT NOT NULL,
  iddetail_penerimaan BIGINT NOT NULL
);
ALTER TABLE detail_retur
ADD PRIMARY KEY (iddetail_retur);
ALTER TABLE detail_retur
ADD CONSTRAINT fk_detretur_retur
FOREIGN KEY (idretur)
REFERENCES retur(idretur);
ALTER TABLE detail_retur
ADD CONSTRAINT fk_detretur_detpenerimaan
FOREIGN KEY (iddetail_penerimaan)
REFERENCES detail_penerimaan(iddetail_penerimaan);


-- ROLE
INSERT INTO role (idrole, nama_role)
VALUES (1, 'Admin'),
(2, 'Kasir'),
(3, 'Staf Gudang');
SELECT * FROM role;

-- USER
INSERT INTO `user` (iduser, username, password, idrole)
VALUES (1, 'admin', SHA2('admin123', 256), 1),
(2, 'kasir01', SHA2('kasir123', 256), 2),
(3, 'gudang01', SHA2('gudang123', 256), 3);
SELECT * FROM `user`;

-- VENDOR
INSERT INTO vendor (idvendor, nama_vendor, badan_hukum, status)
VALUES (1, 'PT Sumber Makmur', 'Y', '1'),
(2, 'CV Berkah Jaya', 'Y', '1'),
(3, 'UD Tani Makmur', 'T', '1');
SELECT * FROM vendor;

-- SATUAN
INSERT INTO satuan (idsatuan, nama_satuan, status)
VALUES (1, 'Pcs', 1),
(2, 'Box', 1),
(3, 'Kg', 1),
(4, 'Liter', 1);
SELECT * FROM satuan;

-- BARANG
INSERT INTO barang (idbarang, jenis, nama, idsatuan, status, harga)
VALUES (1, 'M', 'Mie Instan Goreng', 1, 1, 3500),
(2, 'N', 'Air Mineral 600ml', 1, 1, 4000),
(3, 'M', 'Beras Premium', 3, 1, 68000),
(4, 'N', 'Minyak Goreng', 4, 1, 18000),
(5, 'K', 'Sabun Cuci Piring', 1, 0, 9500);
SELECT * FROM barang;

-- MARGIN PENJUALAN
INSERT INTO margin_penjualan (idmargin_penjualan, persen, status, iduser)
VALUES (1, 10, 0, 1),
(2, 20, 0, 1),
(3, 30, 0, 1),
(4, 40, 0, 1),
(5, 50, 0, 1);
SELECT * FROM margin_penjualan;

UPDATE margin_penjualan SET status = 0;
UPDATE margin_penjualan SET status = 1, updated_at = CURRENT_TIMESTAMP WHERE idmargin_penjualan = 1;


CREATE OR REPLACE VIEW v_role AS
SELECT 
idrole AS id_role,
nama_role
FROM role;

CREATE OR REPLACE VIEW v_user AS
SELECT 
u.iduser AS id_user,
u.username,
r.idrole AS id_role,
r.nama_role
FROM `user` u
JOIN role r ON u.idrole = r.idrole;


CREATE OR REPLACE VIEW v_satuan_all AS
SELECT 
idsatuan AS id_satuan,
nama_satuan,
status,
CASE 
WHEN status = 1 THEN 'Aktif'
ELSE 'Non-Aktif'
END AS ket_status
FROM satuan;


CREATE OR REPLACE VIEW v_satuan_aktif AS
SELECT * FROM v_satuan_all
WHERE status = 1;


CREATE OR REPLACE VIEW v_vendor_all AS
SELECT 
idvendor AS id_vendor,
nama_vendor,
badan_hukum,
CASE 
WHEN badan_hukum = 'Y' THEN 'Berbadan Hukum'
ELSE 'Tidak Berbadan Hukum'
END AS ket_badan_hukum,
status,
CASE 
WHEN status = '1' THEN 'Aktif'
ELSE 'Non-Aktif'
END AS ket_status
FROM vendor;


CREATE OR REPLACE VIEW v_vendor_aktif AS
SELECT * FROM v_vendor_all
WHERE status = '1';


CREATE OR REPLACE VIEW v_barang_all AS
SELECT 
b.idbarang AS id_barang,
b.jenis AS kode_jenis,
CASE 
WHEN b.jenis = 'M' THEN 'Makanan / Pokok'
WHEN b.jenis = 'N' THEN 'Minuman / Cair'
WHEN b.jenis = 'K' THEN 'Kebersihan'
ELSE 'Lainnya'
END AS nama_jenis,
b.nama AS nama_barang,
s.idsatuan AS id_satuan,
s.nama_satuan,
b.harga AS harga_pokok,
b.status,
CASE 
WHEN b.status = 1 THEN 'Aktif'
ELSE 'Non-Aktif'
END AS ket_status
FROM barang b
JOIN satuan s ON b.idsatuan = s.idsatuan;


CREATE OR REPLACE VIEW v_barang_aktif AS
SELECT * FROM v_barang_all
WHERE status = 1;


CREATE OR REPLACE VIEW v_margin_all AS
SELECT 
m.idmargin_penjualan AS id_margin,
m.persen AS persentase_margin,
CONCAT(m.persen, '%') AS tampilan_margin,
m.status,
CASE 
WHEN m.status = 1 THEN 'Aktif'
ELSE 'Non-Aktif'
END AS ket_status,
u.iduser AS id_user,
u.username AS ditetapkan_oleh,
m.created_at,
m.updated_at
FROM margin_penjualan m
JOIN `user` u ON m.iduser = u.iduser;


CREATE OR REPLACE VIEW v_margin_aktif AS
SELECT * FROM v_margin_all
WHERE status = 1;


SELECT * FROM v_role;
SELECT * FROM v_user;
SELECT * FROM v_satuan_all;
SELECT * FROM v_satuan_aktif;
SELECT * FROM v_vendor_all;
SELECT * FROM v_vendor_aktif;
SELECT * FROM v_barang_all;      
SELECT * FROM v_barang_aktif;    
SELECT * FROM v_margin_all;      
SELECT * FROM v_margin_aktif;    