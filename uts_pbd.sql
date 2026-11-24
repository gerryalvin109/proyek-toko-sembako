-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for uts_pbd
CREATE DATABASE IF NOT EXISTS `uts_pbd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `uts_pbd`;

-- Dumping structure for table uts_pbd.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `idbarang` int NOT NULL AUTO_INCREMENT,
  `jenis` char(1) DEFAULT NULL,
  `nama` varchar(45) NOT NULL,
  `idsatuan` int NOT NULL,
  `status` tinyint DEFAULT NULL,
  `harga` int DEFAULT NULL,
  PRIMARY KEY (`idbarang`),
  KEY `fk_barang_satuan` (`idsatuan`),
  CONSTRAINT `fk_barang_satuan` FOREIGN KEY (`idsatuan`) REFERENCES `satuan` (`idsatuan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.barang: ~5 rows (approximately)
INSERT INTO `barang` (`idbarang`, `jenis`, `nama`, `idsatuan`, `status`, `harga`) VALUES
	(1, 'M', 'Mie Instan Goreng', 1, 1, 3500),
	(2, 'N', 'Air Mineral 600ml', 1, 1, 4000),
	(3, 'M', 'Beras Premium', 3, 1, 68000),
	(4, 'N', 'Minyak Goreng', 4, 1, 18000),
	(5, 'K', 'Sabun Cuci Piring', 1, 0, 9500);

-- Dumping structure for table uts_pbd.detail_penerimaan
CREATE TABLE IF NOT EXISTS `detail_penerimaan` (
  `iddetail_penerimaan` bigint NOT NULL AUTO_INCREMENT,
  `idpenerimaan` bigint NOT NULL,
  `barang_idbarang` int NOT NULL,
  `jumlah_terima` int DEFAULT NULL,
  `harga_satuan_terima` int DEFAULT NULL,
  `sub_total_terima` int DEFAULT NULL,
  PRIMARY KEY (`iddetail_penerimaan`),
  KEY `fk_detpenerimaan_penerimaan` (`idpenerimaan`),
  KEY `fk_detpenerimaan_barang` (`barang_idbarang`),
  CONSTRAINT `fk_detpenerimaan_barang` FOREIGN KEY (`barang_idbarang`) REFERENCES `barang` (`idbarang`),
  CONSTRAINT `fk_detpenerimaan_penerimaan` FOREIGN KEY (`idpenerimaan`) REFERENCES `penerimaan` (`idpenerimaan`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.detail_penerimaan: ~5 rows (approximately)
INSERT INTO `detail_penerimaan` (`iddetail_penerimaan`, `idpenerimaan`, `barang_idbarang`, `jumlah_terima`, `harga_satuan_terima`, `sub_total_terima`) VALUES
	(1, 1, 2, 10, 4000, 40000),
	(2, 2, 1, 5, 3500, 17500),
	(3, 3, 3, 3, 68000, 204000),
	(4, 4, 3, 3, 68000, 204000),
	(5, 5, 1, 1, 3500, 3500),
	(6, 6, 1, 10, 3500, 35000),
	(7, 7, 1, 8, 3500, 28000),
	(8, 8, 1, 10, 3500, 35000),
	(9, 9, 2, 2, 4000, 8000),
	(10, 10, 2, 3, 4000, 12000),
	(11, 11, 2, 10, 4000, 40000),
	(12, 12, 4, 3, 18000, 54000),
	(13, 12, 3, 2, 68000, 136000),
	(14, 13, 4, 2, 18000, 36000),
	(15, 13, 3, 8, 68000, 544000),
	(16, 14, 2, 1, 4000, 4000),
	(17, 15, 2, 1, 4000, 4000),
	(18, 16, 2, 3, 4000, 12000),
	(19, 17, 3, 4, 68000, 272000),
	(20, 18, 3, 6, 68000, 408000);

-- Dumping structure for table uts_pbd.detail_pengadaan
CREATE TABLE IF NOT EXISTS `detail_pengadaan` (
  `iddetail_pengadaan` bigint NOT NULL AUTO_INCREMENT,
  `harga_satuan` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `sub_total` int DEFAULT NULL,
  `idbarang` int NOT NULL,
  `idpengadaan` bigint NOT NULL,
  PRIMARY KEY (`iddetail_pengadaan`),
  KEY `fk_detpengadaan_barang` (`idbarang`),
  KEY `fk_detpengadaan_pengadaan` (`idpengadaan`),
  CONSTRAINT `fk_detpengadaan_barang` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  CONSTRAINT `fk_detpengadaan_pengadaan` FOREIGN KEY (`idpengadaan`) REFERENCES `pengadaan` (`idpengadaan`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.detail_pengadaan: ~4 rows (approximately)
INSERT INTO `detail_pengadaan` (`iddetail_pengadaan`, `harga_satuan`, `jumlah`, `sub_total`, `idbarang`, `idpengadaan`) VALUES
	(1, 4000, 10, 40000, 2, 1),
	(2, 3500, 5, 17500, 1, 2),
	(3, 68000, 3, 204000, 3, 3),
	(4, 68000, 3, 204000, 3, 4),
	(5, 3500, 1, 3500, 1, 5),
	(6, 3500, 10, 35000, 1, 6),
	(7, 3500, 8, 28000, 1, 7),
	(8, 3500, 10, 35000, 1, 8),
	(9, 4000, 10, 40000, 2, 9),
	(10, 4000, 5, 20000, 2, 9),
	(11, 18000, 5, 90000, 4, 10),
	(12, 68000, 10, 680000, 3, 10),
	(13, 4000, 5, 20000, 2, 11),
	(14, 68000, 10, 680000, 3, 12);

-- Dumping structure for table uts_pbd.detail_penjualan
CREATE TABLE IF NOT EXISTS `detail_penjualan` (
  `iddetail_penjualan` int NOT NULL AUTO_INCREMENT,
  `harga_satuan` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL,
  `penjualan_idpenjualan` int NOT NULL,
  `idbarang` int NOT NULL,
  PRIMARY KEY (`iddetail_penjualan`),
  KEY `fk_detpenjualan_penjualan` (`penjualan_idpenjualan`),
  KEY `fk_detpenjualan_barang` (`idbarang`),
  CONSTRAINT `fk_detpenjualan_barang` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  CONSTRAINT `fk_detpenjualan_penjualan` FOREIGN KEY (`penjualan_idpenjualan`) REFERENCES `penjualan` (`idpenjualan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.detail_penjualan: ~0 rows (approximately)
INSERT INTO `detail_penjualan` (`iddetail_penjualan`, `harga_satuan`, `jumlah`, `subtotal`, `penjualan_idpenjualan`, `idbarang`) VALUES
	(1, 4400, 5, 22000, 1, 2),
	(2, 74800, 2, 149600, 2, 3),
	(3, 74800, 5, 374000, 3, 3),
	(4, 3850, 4, 15400, 4, 1);

-- Dumping structure for table uts_pbd.detail_retur
CREATE TABLE IF NOT EXISTS `detail_retur` (
  `iddetail_retur` int NOT NULL AUTO_INCREMENT,
  `jumlah` int DEFAULT NULL,
  `alasan` varchar(200) DEFAULT NULL,
  `idretur` bigint NOT NULL,
  `iddetail_penerimaan` bigint NOT NULL,
  PRIMARY KEY (`iddetail_retur`),
  KEY `fk_detretur_retur` (`idretur`),
  KEY `fk_detretur_detpenerimaan` (`iddetail_penerimaan`),
  CONSTRAINT `fk_detretur_detpenerimaan` FOREIGN KEY (`iddetail_penerimaan`) REFERENCES `detail_penerimaan` (`iddetail_penerimaan`),
  CONSTRAINT `fk_detretur_retur` FOREIGN KEY (`idretur`) REFERENCES `retur` (`idretur`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.detail_retur: ~0 rows (approximately)
INSERT INTO `detail_retur` (`iddetail_retur`, `jumlah`, `alasan`, `idretur`, `iddetail_penerimaan`) VALUES
	(1, 1, 'kemasan rusak', 1, 14),
	(2, 5, 'expired', 2, 15),
	(3, 3, '', 3, 15),
	(4, 1, '', 4, 14),
	(5, 1, '', 5, 12),
	(6, 1, '', 5, 13);

-- Dumping structure for function uts_pbd.fn_cek_stok_barang
DELIMITER //
CREATE FUNCTION `fn_cek_stok_barang`(p_idbarang INT) RETURNS int
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE v_stok INT DEFAULT 0;

    SELECT stock INTO v_stok
    FROM kartu_stok
    WHERE idbarang = p_idbarang
    ORDER BY idkartu_stok DESC
    LIMIT 1;

    RETURN COALESCE(v_stok, 0);
END//
DELIMITER ;

-- Dumping structure for function uts_pbd.fn_hitung_harga_jual
DELIMITER //
CREATE FUNCTION `fn_hitung_harga_jual`(p_harga_pokok INT, p_persen DOUBLE) RETURNS int
    DETERMINISTIC
BEGIN
    RETURN ROUND(p_harga_pokok + (p_harga_pokok * p_persen / 100));
END//
DELIMITER ;

-- Dumping structure for function uts_pbd.fn_hitung_ppn
DELIMITER //
CREATE FUNCTION `fn_hitung_ppn`(p_subtotal INT) RETURNS int
    DETERMINISTIC
BEGIN
    RETURN ROUND(p_subtotal * 0.11);
END//
DELIMITER ;

-- Dumping structure for table uts_pbd.kartu_stok
CREATE TABLE IF NOT EXISTS `kartu_stok` (
  `idkartu_stok` bigint NOT NULL AUTO_INCREMENT,
  `jenis_transaksi` char(1) DEFAULT NULL,
  `masuk` int DEFAULT NULL,
  `keluar` int DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `idtransaksi` int DEFAULT NULL,
  `idbarang` int NOT NULL,
  PRIMARY KEY (`idkartu_stok`),
  KEY `fk_kartustok_barang` (`idbarang`),
  CONSTRAINT `fk_kartustok_barang` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.kartu_stok: ~5 rows (approximately)
INSERT INTO `kartu_stok` (`idkartu_stok`, `jenis_transaksi`, `masuk`, `keluar`, `stock`, `created_at`, `idtransaksi`, `idbarang`) VALUES
	(1, 'M', 10, 0, 10, '2026-11-03 02:45:25', 1, 2),
	(2, 'M', 5, 0, 5, '2026-11-03 02:45:41', 2, 1),
	(3, 'M', 3, 0, 3, '2026-11-03 07:24:57', 3, 3),
	(4, 'M', 3, 0, 6, '2026-11-03 07:34:52', 4, 3),
	(5, 'M', 1, 0, 6, '2026-11-03 07:34:59', 5, 1),
	(6, 'M', 10, 0, 16, '2026-11-23 14:26:15', 6, 1),
	(7, 'M', 8, 0, 24, '2026-11-23 14:26:29', 7, 1),
	(8, 'M', 10, 0, 34, '2026-11-23 15:19:16', 8, 1),
	(9, 'M', 2, 0, 12, '2026-11-23 15:31:36', 9, 2),
	(10, 'M', 3, 0, 15, '2026-11-23 15:32:10', 10, 2),
	(11, 'M', 10, 0, 25, '2026-11-23 15:39:06', 11, 2),
	(12, 'M', 3, 0, 3, '2026-11-23 15:45:41', 12, 4),
	(13, 'M', 2, 0, 8, '2026-11-23 15:45:41', 12, 3),
	(14, 'M', 2, 0, 5, '2026-11-23 15:45:51', 13, 4),
	(15, 'M', 8, 0, 16, '2026-11-23 15:45:51', 13, 3),
	(16, 'K', 0, 1, 4, '2026-11-23 15:52:36', 4, 4),
	(17, 'K', 0, 5, 20, '2026-11-23 16:00:02', 1, 2),
	(18, 'K', 0, 2, 14, '2026-11-23 16:01:15', 2, 3),
	(19, 'K', 0, 1, 3, '2026-11-24 02:58:25', 5, 4),
	(20, 'K', 0, 1, 13, '2026-11-24 02:58:25', 5, 3),
	(21, 'K', 0, 5, 8, '2026-11-24 02:59:11', 3, 3),
	(22, 'M', 1, 0, 21, '2026-11-24 03:09:35', 14, 2),
	(23, 'M', 1, 0, 22, '2026-11-24 03:09:40', 15, 2),
	(24, 'M', 3, 0, 25, '2026-11-24 03:09:44', 16, 2),
	(25, 'K', 0, 4, 30, '2026-11-24 03:10:17', 4, 1),
	(26, 'M', 4, 0, 12, '2026-11-24 03:11:06', 17, 3),
	(27, 'M', 6, 0, 18, '2026-11-24 03:11:16', 18, 3);

-- Dumping structure for table uts_pbd.margin_penjualan
CREATE TABLE IF NOT EXISTS `margin_penjualan` (
  `idmargin_penjualan` int NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `persen` double DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `iduser` int NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idmargin_penjualan`),
  KEY `fk_margin_user` (`iduser`),
  CONSTRAINT `fk_margin_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.margin_penjualan: ~5 rows (approximately)
INSERT INTO `margin_penjualan` (`idmargin_penjualan`, `created_at`, `persen`, `status`, `iduser`, `updated_at`) VALUES
	(1, '2026-10-09 06:20:55', 10, 1, 1, '2026-11-10 05:15:09'),
	(2, '2026-10-09 06:20:55', 20, 0, 1, '2026-11-10 05:37:42'),
	(3, '2026-10-09 06:20:55', 30, 0, 1, '2026-11-03 06:33:33'),
	(4, '2026-10-09 06:20:55', 40, 0, 1, '2026-11-02 04:33:40'),
	(5, '2026-10-09 06:20:55', 50, 0, 1, '2026-11-03 06:33:37');

-- Dumping structure for table uts_pbd.penerimaan
CREATE TABLE IF NOT EXISTS `penerimaan` (
  `idpenerimaan` bigint NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` char(1) DEFAULT NULL,
  `idpengadaan` bigint NOT NULL,
  `iduser` int NOT NULL,
  PRIMARY KEY (`idpenerimaan`),
  KEY `fk_penerimaan_pengadaan` (`idpengadaan`),
  KEY `fk_penerimaan_user` (`iduser`),
  CONSTRAINT `fk_penerimaan_pengadaan` FOREIGN KEY (`idpengadaan`) REFERENCES `pengadaan` (`idpengadaan`),
  CONSTRAINT `fk_penerimaan_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.penerimaan: ~4 rows (approximately)
INSERT INTO `penerimaan` (`idpenerimaan`, `created_at`, `status`, `idpengadaan`, `iduser`) VALUES
	(1, '2026-11-03 02:45:25', '1', 1, 3),
	(2, '2026-11-03 02:45:41', '1', 2, 2),
	(3, '2026-11-03 07:24:57', '1', 3, 2),
	(4, '2026-11-03 07:34:52', '1', 4, 2),
	(5, '2026-11-03 07:34:59', '1', 5, 1),
	(6, '2026-11-23 14:26:15', '1', 6, 1),
	(7, '2026-11-23 14:26:29', '1', 7, 1),
	(8, '2026-11-23 15:19:16', '1', 8, 1),
	(9, '2026-11-23 15:31:36', 'S', 9, 1),
	(10, '2026-11-23 15:32:10', 'S', 9, 1),
	(11, '2026-11-23 15:39:06', 'S', 9, 1),
	(12, '2026-11-23 15:45:41', 'S', 10, 1),
	(13, '2026-11-23 15:45:51', 'S', 10, 1),
	(14, '2026-11-24 03:09:35', 'S', 11, 1),
	(15, '2026-11-24 03:09:40', 'S', 11, 1),
	(16, '2026-11-24 03:09:44', 'S', 11, 1),
	(17, '2026-11-24 03:11:06', 'S', 12, 3),
	(18, '2026-11-24 03:11:16', 'S', 12, 3);

-- Dumping structure for table uts_pbd.pengadaan
CREATE TABLE IF NOT EXISTS `pengadaan` (
  `idpengadaan` bigint NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_iduser` int NOT NULL,
  `status` char(1) DEFAULT NULL,
  `vendor_idvendor` int NOT NULL,
  `subtotal_nilai` int DEFAULT NULL,
  `ppn` int DEFAULT NULL,
  `total_nilai` int DEFAULT NULL,
  PRIMARY KEY (`idpengadaan`),
  KEY `fk_pengadaan_user` (`user_iduser`),
  KEY `fk_pengadaan_vendor` (`vendor_idvendor`),
  CONSTRAINT `fk_pengadaan_user` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`),
  CONSTRAINT `fk_pengadaan_vendor` FOREIGN KEY (`vendor_idvendor`) REFERENCES `vendor` (`idvendor`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.pengadaan: ~4 rows (approximately)
INSERT INTO `pengadaan` (`idpengadaan`, `timestamp`, `user_iduser`, `status`, `vendor_idvendor`, `subtotal_nilai`, `ppn`, `total_nilai`) VALUES
	(1, '2026-11-03 02:45:25', 3, 'S', 3, 40000, 4000, 44000),
	(2, '2026-11-03 02:45:41', 2, 'S', 3, 17500, 1750, 19250),
	(3, '2026-11-03 07:24:57', 2, 'S', 2, 204000, 20400, 224400),
	(4, '2026-11-03 07:34:52', 2, 'S', 2, 204000, 20400, 224400),
	(5, '2026-11-03 07:34:59', 1, 'S', 1, 3500, 350, 3850),
	(6, '2026-11-23 14:26:15', 1, 'S', 1, 35000, 3500, 38500),
	(7, '2026-11-23 14:26:29', 1, 'S', 1, 28000, 2800, 30800),
	(8, '2026-11-23 15:19:16', 1, 'S', 1, 35000, 3500, 38500),
	(9, '2026-11-23 15:31:23', 1, 'S', 2, 60000, 6600, 66600),
	(10, '2026-11-23 15:43:11', 1, 'S', 3, 770000, 84700, 854700),
	(11, '2026-11-24 03:09:28', 1, 'S', 2, 20000, 2200, 22200),
	(12, '2026-11-24 03:11:00', 3, 'S', 1, 680000, 74800, 754800);

-- Dumping structure for table uts_pbd.penjualan
CREATE TABLE IF NOT EXISTS `penjualan` (
  `idpenjualan` int NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `subtotal_nilai` int DEFAULT NULL,
  `ppn` int DEFAULT NULL,
  `total_nilai` int DEFAULT NULL,
  `iduser` int NOT NULL,
  `idmargin_penjualan` int NOT NULL,
  PRIMARY KEY (`idpenjualan`),
  KEY `fk_penjualan_user` (`iduser`),
  KEY `fk_penjualan_margin` (`idmargin_penjualan`),
  CONSTRAINT `fk_penjualan_margin` FOREIGN KEY (`idmargin_penjualan`) REFERENCES `margin_penjualan` (`idmargin_penjualan`),
  CONSTRAINT `fk_penjualan_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.penjualan: ~0 rows (approximately)
INSERT INTO `penjualan` (`idpenjualan`, `created_at`, `subtotal_nilai`, `ppn`, `total_nilai`, `iduser`, `idmargin_penjualan`) VALUES
	(1, '2026-11-23 16:00:02', 22000, 2420, 24420, 1, 1),
	(2, '2026-11-23 16:01:15', 149600, 16456, 166056, 2, 1),
	(3, '2026-11-24 02:59:11', 374000, 41140, 415140, 2, 1),
	(4, '2026-11-24 03:10:17', 15400, 1694, 17094, 2, 1);

-- Dumping structure for table uts_pbd.retur
CREATE TABLE IF NOT EXISTS `retur` (
  `idretur` bigint NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `idpenerimaan` bigint NOT NULL,
  `iduser` int NOT NULL,
  PRIMARY KEY (`idretur`),
  KEY `fk_retur_penerimaan` (`idpenerimaan`),
  KEY `fk_retur_user` (`iduser`),
  CONSTRAINT `fk_retur_penerimaan` FOREIGN KEY (`idpenerimaan`) REFERENCES `penerimaan` (`idpenerimaan`),
  CONSTRAINT `fk_retur_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.retur: ~0 rows (approximately)
INSERT INTO `retur` (`idretur`, `created_at`, `idpenerimaan`, `iduser`) VALUES
	(1, '2026-11-23 15:48:49', 13, 1),
	(2, '2026-11-23 15:50:19', 13, 1),
	(3, '2026-11-23 15:51:12', 13, 1),
	(4, '2026-11-23 15:52:36', 13, 1),
	(5, '2026-11-24 02:58:25', 12, 3);

-- Dumping structure for table uts_pbd.role
CREATE TABLE IF NOT EXISTS `role` (
  `idrole` int NOT NULL AUTO_INCREMENT,
  `nama_role` varchar(100) NOT NULL,
  PRIMARY KEY (`idrole`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.role: ~3 rows (approximately)
INSERT INTO `role` (`idrole`, `nama_role`) VALUES
	(1, 'Admin'),
	(2, 'Kasir'),
	(3, 'Staf Gudang');

-- Dumping structure for table uts_pbd.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `idsatuan` int NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(45) NOT NULL,
  `status` tinyint DEFAULT NULL,
  PRIMARY KEY (`idsatuan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.satuan: ~4 rows (approximately)
INSERT INTO `satuan` (`idsatuan`, `nama_satuan`, `status`) VALUES
	(1, 'Pcs', 1),
	(2, 'Box', 1),
	(3, 'Kg', 1),
	(4, 'Liter', 1);

-- Dumping structure for procedure uts_pbd.sp_catat_penerimaan_barang
DELIMITER //
CREATE PROCEDURE `sp_catat_penerimaan_barang`(
    IN p_idvendor INT,
    IN p_iduser INT,
    IN p_idbarang INT,
    IN p_jumlah INT
)
BEGIN
    DECLARE v_harga INT DEFAULT 0;
    DECLARE v_subtotal INT DEFAULT 0;
    DECLARE v_ppn INT DEFAULT 0;
    DECLARE v_total INT DEFAULT 0;
    DECLARE v_idpengadaan BIGINT;
    DECLARE v_idpenerimaan BIGINT;

    SELECT harga INTO v_harga FROM barang WHERE idbarang = p_idbarang;
    SET v_subtotal = v_harga * p_jumlah;
    SET v_ppn = ROUND(v_subtotal * 0.10);
    SET v_total = v_subtotal + v_ppn;

    INSERT INTO pengadaan (timestamp, user_iduser, status, vendor_idvendor, subtotal_nilai, ppn, total_nilai)
    VALUES (CURRENT_TIMESTAMP, p_iduser, 'S', p_idvendor, v_subtotal, v_ppn, v_total);
    SET v_idpengadaan = LAST_INSERT_ID();

    INSERT INTO detail_pengadaan (harga_satuan, jumlah, sub_total, idbarang, idpengadaan)
    VALUES (v_harga, p_jumlah, v_subtotal, p_idbarang, v_idpengadaan);

    INSERT INTO penerimaan (created_at, status, idpengadaan, iduser)
    VALUES (CURRENT_TIMESTAMP, '1', v_idpengadaan, p_iduser);
    SET v_idpenerimaan = LAST_INSERT_ID();

    INSERT INTO detail_penerimaan (idpenerimaan, barang_idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
    VALUES (v_idpenerimaan, p_idbarang, p_jumlah, v_harga, v_subtotal);
END//
DELIMITER ;

-- Dumping structure for procedure uts_pbd.sp_update_status_pengadaan
DELIMITER //
CREATE PROCEDURE `sp_update_status_pengadaan`(IN p_idpengadaan INT)
BEGIN
    DECLARE v_dipesan INT DEFAULT 0;
    DECLARE v_diterima INT DEFAULT 0;

    SELECT COALESCE(SUM(jumlah), 0) INTO v_dipesan
    FROM detail_pengadaan
    WHERE idpengadaan = p_idpengadaan;

    SELECT COALESCE(SUM(dpr.jumlah_terima), 0) INTO v_diterima
    FROM detail_penerimaan dpr
    JOIN penerimaan pr ON dpr.idpenerimaan = pr.idpenerimaan
    WHERE pr.idpengadaan = p_idpengadaan;

    IF v_dipesan > 0 AND v_diterima >= v_dipesan THEN
        UPDATE pengadaan SET status = 'S' WHERE idpengadaan = p_idpengadaan;
        UPDATE penerimaan SET status = 'S' WHERE idpengadaan = p_idpengadaan;
    ELSE
        UPDATE pengadaan SET status = 'D' WHERE idpengadaan = p_idpengadaan;
    END IF;
END//
DELIMITER ;

-- Dumping structure for table uts_pbd.user
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `idrole` int NOT NULL,
  PRIMARY KEY (`iduser`),
  KEY `fk_user_role` (`idrole`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.user: ~3 rows (approximately)
INSERT INTO `user` (`iduser`, `username`, `password`, `idrole`) VALUES
	(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 1),
	(2, 'kasir01', 'f02b7c1e519e4fa436147f7e1399974f9510aa9c8e0cb8be29151eb540f9d214', 2),
	(3, 'gudang01', '1a62eac618f519df0f710271f67285afe8bab689f9c7d902157c50a9c90f4f9e', 3);

-- Dumping structure for table uts_pbd.vendor
CREATE TABLE IF NOT EXISTS `vendor` (
  `idvendor` int NOT NULL AUTO_INCREMENT,
  `nama_vendor` varchar(100) NOT NULL,
  `badan_hukum` char(1) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`idvendor`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table uts_pbd.vendor: ~3 rows (approximately)
INSERT INTO `vendor` (`idvendor`, `nama_vendor`, `badan_hukum`, `status`) VALUES
	(1, 'PT Sumber Makmur', 'Y', '1'),
	(2, 'CV Berkah Jaya', 'Y', '1'),
	(3, 'UD Tani Makmur', 'T', '1');

-- Dumping structure for view uts_pbd.v_barang_aktif
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_barang_aktif` (
	`id_barang` INT NOT NULL,
	`kode_jenis` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`nama_jenis` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`id_satuan` INT NOT NULL,
	`nama_satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`harga_pokok` INT NULL,
	`status` TINYINT NULL,
	`ket_status` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_barang_all
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_barang_all` (
	`id_barang` INT NOT NULL,
	`kode_jenis` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`nama_jenis` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`id_satuan` INT NOT NULL,
	`nama_satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`harga_pokok` INT NULL,
	`status` TINYINT NULL,
	`ket_status` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_detail_penerimaan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_detail_penerimaan` (
	`iddetail_penerimaan` BIGINT NOT NULL,
	`idpenerimaan` BIGINT NOT NULL,
	`barang_idbarang` INT NOT NULL,
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`jumlah_terima` INT NULL,
	`harga_satuan_terima` INT NULL,
	`sub_total_terima` INT NULL
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_detail_pengadaan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_detail_pengadaan` (
	`iddetail_pengadaan` BIGINT NOT NULL,
	`idpengadaan` BIGINT NOT NULL,
	`idbarang` INT NOT NULL,
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`harga_satuan` INT NULL,
	`jumlah` INT NULL,
	`sub_total` INT NULL
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_detail_penjualan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_detail_penjualan` (
	`iddetail_penjualan` INT NOT NULL,
	`penjualan_idpenjualan` INT NOT NULL,
	`idbarang` INT NOT NULL,
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`harga_satuan` INT NULL,
	`jumlah` INT NULL,
	`subtotal` INT NULL
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_detail_retur
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_detail_retur` (
	`iddetail_retur` INT NOT NULL,
	`idretur` BIGINT NOT NULL,
	`iddetail_penerimaan` BIGINT NOT NULL,
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`jumlah` INT NULL,
	`alasan` VARCHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_harga_jual_barang
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_harga_jual_barang` (
	`id_barang` INT NOT NULL,
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`nama_satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`harga_pokok` INT NULL,
	`persen_margin` DOUBLE NULL,
	`harga_jual` DOUBLE NULL
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_kartu_stok
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_kartu_stok` (
	`idkartu_stok` BIGINT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`idbarang` INT NOT NULL,
	`nama_barang` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`jenis_transaksi` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`masuk` INT NULL,
	`keluar` INT NULL,
	`stock` INT NULL,
	`idtransaksi` INT NULL
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_margin_aktif
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_margin_aktif` 
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_margin_all
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_margin_all` (
	`id_margin` INT NOT NULL,
	`persen` DOUBLE NULL,
	`status` TINYINT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	`iduser` INT NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_penerimaan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_penerimaan` (
	`idpenerimaan` BIGINT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`status` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`idpengadaan` BIGINT NOT NULL,
	`iduser` INT NOT NULL,
	`petugas_penerima` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_pengadaan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pengadaan` (
	`idpengadaan` BIGINT NOT NULL,
	`timestamp` TIMESTAMP NULL,
	`user_iduser` INT NOT NULL,
	`petugas` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`vendor_idvendor` INT NOT NULL,
	`nama_vendor` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`subtotal_nilai` INT NULL,
	`ppn` INT NULL,
	`total_nilai` INT NULL,
	`status` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_penjualan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_penjualan` (
	`idpenjualan` INT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`iduser` INT NOT NULL,
	`kasir` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`idmargin_penjualan` INT NOT NULL,
	`persen_margin` DOUBLE NULL,
	`subtotal_nilai` INT NULL,
	`ppn` INT NULL,
	`total_nilai` INT NULL
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_retur
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_retur` (
	`idretur` BIGINT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`idpenerimaan` BIGINT NOT NULL,
	`iduser` INT NOT NULL,
	`petugas` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_role
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_role` (
	`id_role` INT NOT NULL,
	`nama_role` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_satuan_aktif
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_satuan_aktif` (
	`id_satuan` INT NOT NULL,
	`nama_satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`status` TINYINT NULL,
	`ket_status` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_satuan_all
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_satuan_all` (
	`id_satuan` INT NOT NULL,
	`nama_satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`status` TINYINT NULL,
	`ket_status` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_user
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_user` (
	`id_user` INT NOT NULL,
	`username` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`id_role` INT NOT NULL,
	`nama_role` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_vendor_aktif
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_vendor_aktif` (
	`id_vendor` INT NOT NULL,
	`nama_vendor` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`badan_hukum` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`ket_badan_hukum` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`status` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`ket_status` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view uts_pbd.v_vendor_all
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_vendor_all` (
	`id_vendor` INT NOT NULL,
	`nama_vendor` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`badan_hukum` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`ket_badan_hukum` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`status` CHAR(1) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`ket_status` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for trigger uts_pbd.trg_after_insert_detail_penerimaan
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_after_insert_detail_penerimaan` AFTER INSERT ON `detail_penerimaan` FOR EACH ROW BEGIN
    DECLARE v_stok_akhir INT DEFAULT 0;

    SELECT COALESCE(stock, 0) INTO v_stok_akhir
    FROM kartu_stok
    WHERE idbarang = NEW.barang_idbarang
    ORDER BY idkartu_stok DESC
    LIMIT 1;

    INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
    VALUES ('M', NEW.jumlah_terima, 0, v_stok_akhir + NEW.jumlah_terima, NOW(), NEW.idpenerimaan, NEW.barang_idbarang);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger uts_pbd.trg_after_insert_detail_penjualan
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_after_insert_detail_penjualan` AFTER INSERT ON `detail_penjualan` FOR EACH ROW BEGIN
    DECLARE v_stok_akhir INT DEFAULT 0;

    SELECT COALESCE(stock, 0) INTO v_stok_akhir
    FROM kartu_stok
    WHERE idbarang = NEW.idbarang
    ORDER BY idkartu_stok DESC
    LIMIT 1;

    INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
    VALUES ('K', 0, NEW.jumlah, GREATEST(0, v_stok_akhir - NEW.jumlah), NOW(), NEW.penjualan_idpenjualan, NEW.idbarang);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger uts_pbd.trg_after_insert_detail_retur
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_after_insert_detail_retur` AFTER INSERT ON `detail_retur` FOR EACH ROW BEGIN
    DECLARE v_idbarang INT;
    DECLARE v_stok_akhir INT DEFAULT 0;

    -- 1. Ambil idbarang dari detail_penerimaan
    SELECT barang_idbarang INTO v_idbarang
    FROM detail_penerimaan
    WHERE iddetail_penerimaan = NEW.iddetail_penerimaan
    LIMIT 1;

    -- 2. Ambil stok terakhir barang tersebut di kartu_stok
    SELECT COALESCE(stock, 0) INTO v_stok_akhir
    FROM kartu_stok
    WHERE idbarang = v_idbarang
    ORDER BY idkartu_stok DESC
    LIMIT 1;

    -- 3. Catat mutasi keluar (K) ke kartu_stok
    INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
    VALUES ('K', 0, NEW.jumlah, GREATEST(0, v_stok_akhir - NEW.jumlah), NOW(), NEW.idretur, v_idbarang);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_barang_aktif`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_barang_aktif` AS select `v_barang_all`.`id_barang` AS `id_barang`,`v_barang_all`.`kode_jenis` AS `kode_jenis`,`v_barang_all`.`nama_jenis` AS `nama_jenis`,`v_barang_all`.`nama_barang` AS `nama_barang`,`v_barang_all`.`id_satuan` AS `id_satuan`,`v_barang_all`.`nama_satuan` AS `nama_satuan`,`v_barang_all`.`harga_pokok` AS `harga_pokok`,`v_barang_all`.`status` AS `status`,`v_barang_all`.`ket_status` AS `ket_status` from `v_barang_all` where (`v_barang_all`.`status` = 1);

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_barang_all`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_barang_all` AS select `b`.`idbarang` AS `id_barang`,`b`.`jenis` AS `kode_jenis`,(case when (`b`.`jenis` = 'M') then 'Makanan / Pokok' when (`b`.`jenis` = 'N') then 'Minuman / Cair' when (`b`.`jenis` = 'K') then 'Kebersihan' else 'Lainnya' end) AS `nama_jenis`,`b`.`nama` AS `nama_barang`,`s`.`idsatuan` AS `id_satuan`,`s`.`nama_satuan` AS `nama_satuan`,`b`.`harga` AS `harga_pokok`,`b`.`status` AS `status`,(case when (`b`.`status` = 1) then 'Aktif' else 'Non-Aktif' end) AS `ket_status` from (`barang` `b` join `satuan` `s` on((`b`.`idsatuan` = `s`.`idsatuan`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_detail_penerimaan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_detail_penerimaan` AS select `dpr`.`iddetail_penerimaan` AS `iddetail_penerimaan`,`dpr`.`idpenerimaan` AS `idpenerimaan`,`dpr`.`barang_idbarang` AS `barang_idbarang`,`b`.`nama` AS `nama_barang`,`dpr`.`jumlah_terima` AS `jumlah_terima`,`dpr`.`harga_satuan_terima` AS `harga_satuan_terima`,`dpr`.`sub_total_terima` AS `sub_total_terima` from (`detail_penerimaan` `dpr` join `barang` `b` on((`dpr`.`barang_idbarang` = `b`.`idbarang`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_detail_pengadaan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_detail_pengadaan` AS select `dp`.`iddetail_pengadaan` AS `iddetail_pengadaan`,`dp`.`idpengadaan` AS `idpengadaan`,`dp`.`idbarang` AS `idbarang`,`b`.`nama` AS `nama_barang`,`dp`.`harga_satuan` AS `harga_satuan`,`dp`.`jumlah` AS `jumlah`,`dp`.`sub_total` AS `sub_total` from (`detail_pengadaan` `dp` join `barang` `b` on((`dp`.`idbarang` = `b`.`idbarang`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_detail_penjualan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_detail_penjualan` AS select `dpj`.`iddetail_penjualan` AS `iddetail_penjualan`,`dpj`.`penjualan_idpenjualan` AS `penjualan_idpenjualan`,`dpj`.`idbarang` AS `idbarang`,`b`.`nama` AS `nama_barang`,`dpj`.`harga_satuan` AS `harga_satuan`,`dpj`.`jumlah` AS `jumlah`,`dpj`.`subtotal` AS `subtotal` from (`detail_penjualan` `dpj` join `barang` `b` on((`dpj`.`idbarang` = `b`.`idbarang`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_detail_retur`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_detail_retur` AS select `dr`.`iddetail_retur` AS `iddetail_retur`,`dr`.`idretur` AS `idretur`,`dr`.`iddetail_penerimaan` AS `iddetail_penerimaan`,`b`.`nama` AS `nama_barang`,`dr`.`jumlah` AS `jumlah`,`dr`.`alasan` AS `alasan` from ((`detail_retur` `dr` join `detail_penerimaan` `dpr` on((`dr`.`iddetail_penerimaan` = `dpr`.`iddetail_penerimaan`))) join `barang` `b` on((`dpr`.`barang_idbarang` = `b`.`idbarang`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_harga_jual_barang`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_harga_jual_barang` AS select `b`.`idbarang` AS `id_barang`,`b`.`nama` AS `nama_barang`,`s`.`nama_satuan` AS `nama_satuan`,`b`.`harga` AS `harga_pokok`,`m`.`persen` AS `persen_margin`,round((`b`.`harga` + ((`b`.`harga` * `m`.`persen`) / 100)),0) AS `harga_jual` from ((`barang` `b` join `satuan` `s` on((`b`.`idsatuan` = `s`.`idsatuan`))) join `margin_penjualan` `m` on((`m`.`status` = 1))) where (`b`.`status` = 1);

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_kartu_stok`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_kartu_stok` AS select `k`.`idkartu_stok` AS `idkartu_stok`,`k`.`created_at` AS `created_at`,`k`.`idbarang` AS `idbarang`,`b`.`nama` AS `nama_barang`,`k`.`jenis_transaksi` AS `jenis_transaksi`,`k`.`masuk` AS `masuk`,`k`.`keluar` AS `keluar`,`k`.`stock` AS `stock`,`k`.`idtransaksi` AS `idtransaksi` from (`kartu_stok` `k` join `barang` `b` on((`k`.`idbarang` = `b`.`idbarang`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_margin_aktif`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_margin_aktif` AS select `v_margin_all`.`id_margin` AS `id_margin`,`v_margin_all`.`persentase_margin` AS `persentase_margin`,`v_margin_all`.`tampilan_margin` AS `tampilan_margin`,`v_margin_all`.`status` AS `status`,`v_margin_all`.`ket_status` AS `ket_status`,`v_margin_all`.`id_user` AS `id_user`,`v_margin_all`.`ditetapkan_oleh` AS `ditetapkan_oleh`,`v_margin_all`.`created_at` AS `created_at`,`v_margin_all`.`updated_at` AS `updated_at` from `v_margin_all` where (`v_margin_all`.`status` = 1);

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_margin_all`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_margin_all` AS select `margin_penjualan`.`idmargin_penjualan` AS `id_margin`,`margin_penjualan`.`persen` AS `persen`,`margin_penjualan`.`status` AS `status`,`margin_penjualan`.`created_at` AS `created_at`,`margin_penjualan`.`updated_at` AS `updated_at`,`margin_penjualan`.`iduser` AS `iduser` from `margin_penjualan`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_penerimaan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_penerimaan` AS select `pr`.`idpenerimaan` AS `idpenerimaan`,`pr`.`created_at` AS `created_at`,`pr`.`status` AS `status`,`pr`.`idpengadaan` AS `idpengadaan`,`pr`.`iduser` AS `iduser`,`u`.`username` AS `petugas_penerima` from (`penerimaan` `pr` join `user` `u` on((`pr`.`iduser` = `u`.`iduser`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pengadaan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pengadaan` AS select `p`.`idpengadaan` AS `idpengadaan`,`p`.`timestamp` AS `timestamp`,`p`.`user_iduser` AS `user_iduser`,`u`.`username` AS `petugas`,`p`.`vendor_idvendor` AS `vendor_idvendor`,`v`.`nama_vendor` AS `nama_vendor`,`p`.`subtotal_nilai` AS `subtotal_nilai`,`p`.`ppn` AS `ppn`,`p`.`total_nilai` AS `total_nilai`,`p`.`status` AS `status` from ((`pengadaan` `p` join `user` `u` on((`p`.`user_iduser` = `u`.`iduser`))) join `vendor` `v` on((`p`.`vendor_idvendor` = `v`.`idvendor`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_penjualan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_penjualan` AS select `pj`.`idpenjualan` AS `idpenjualan`,`pj`.`created_at` AS `created_at`,`pj`.`iduser` AS `iduser`,`u`.`username` AS `kasir`,`pj`.`idmargin_penjualan` AS `idmargin_penjualan`,`m`.`persen` AS `persen_margin`,`pj`.`subtotal_nilai` AS `subtotal_nilai`,`pj`.`ppn` AS `ppn`,`pj`.`total_nilai` AS `total_nilai` from ((`penjualan` `pj` join `user` `u` on((`pj`.`iduser` = `u`.`iduser`))) join `margin_penjualan` `m` on((`pj`.`idmargin_penjualan` = `m`.`idmargin_penjualan`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_retur`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_retur` AS select `r`.`idretur` AS `idretur`,`r`.`created_at` AS `created_at`,`r`.`idpenerimaan` AS `idpenerimaan`,`r`.`iduser` AS `iduser`,`u`.`username` AS `petugas` from (`retur` `r` join `user` `u` on((`r`.`iduser` = `u`.`iduser`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_role`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_role` AS select `role`.`idrole` AS `id_role`,`role`.`nama_role` AS `nama_role` from `role`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_satuan_aktif`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_satuan_aktif` AS select `v_satuan_all`.`id_satuan` AS `id_satuan`,`v_satuan_all`.`nama_satuan` AS `nama_satuan`,`v_satuan_all`.`status` AS `status`,`v_satuan_all`.`ket_status` AS `ket_status` from `v_satuan_all` where (`v_satuan_all`.`status` = 1);

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_satuan_all`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_satuan_all` AS select `satuan`.`idsatuan` AS `id_satuan`,`satuan`.`nama_satuan` AS `nama_satuan`,`satuan`.`status` AS `status`,(case when (`satuan`.`status` = 1) then 'Aktif' else 'Non-Aktif' end) AS `ket_status` from `satuan`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_user`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_user` AS select `u`.`iduser` AS `id_user`,`u`.`username` AS `username`,`r`.`idrole` AS `id_role`,`r`.`nama_role` AS `nama_role` from (`user` `u` join `role` `r` on((`u`.`idrole` = `r`.`idrole`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_vendor_aktif`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_vendor_aktif` AS select `v_vendor_all`.`id_vendor` AS `id_vendor`,`v_vendor_all`.`nama_vendor` AS `nama_vendor`,`v_vendor_all`.`badan_hukum` AS `badan_hukum`,`v_vendor_all`.`ket_badan_hukum` AS `ket_badan_hukum`,`v_vendor_all`.`status` AS `status`,`v_vendor_all`.`ket_status` AS `ket_status` from `v_vendor_all` where (`v_vendor_all`.`status` = '1');

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_vendor_all`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_vendor_all` AS select `vendor`.`idvendor` AS `id_vendor`,`vendor`.`nama_vendor` AS `nama_vendor`,`vendor`.`badan_hukum` AS `badan_hukum`,(case when (`vendor`.`badan_hukum` = 'Y') then 'Berbadan Hukum' else 'Tidak Berbadan Hukum' end) AS `ket_badan_hukum`,`vendor`.`status` AS `status`,(case when (`vendor`.`status` = '1') then 'Aktif' else 'Non-Aktif' end) AS `ket_status` from `vendor`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
