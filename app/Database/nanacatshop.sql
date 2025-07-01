/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - nanacatshop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`nanacatshop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `nanacatshop`;

/*Table structure for table `barang` */

DROP TABLE IF EXISTS `barang`;

CREATE TABLE `barang` (
  `kdbarang` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `namabarang` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `foto` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hargabeli` double NOT NULL DEFAULT '0',
  `hargajual` double NOT NULL DEFAULT '0',
  `satuan` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kdkategori` char(7) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdbarang`),
  KEY `barang_kdkategori_foreign` (`kdkategori`),
  CONSTRAINT `barang_kdkategori_foreign` FOREIGN KEY (`kdkategori`) REFERENCES `kategori` (`kdkategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `barang` */

insert  into `barang`(`kdbarang`,`namabarang`,`jumlah`,`foto`,`hargabeli`,`hargajual`,`satuan`,`kdkategori`,`created_at`,`updated_at`) values 
('BRG202506290001','Kalung Kucing',99,'1751215214_5ffa84583bb2403b796c.jpg',5000,10000,'Pcs','KTG2501','2025-06-29 16:40:14','2025-06-29 17:03:55'),
('BRG202506290002','Cat Choize',99,'1751215278_58b132fa28ed23637107.jpg',15000,25000,'Pcs','KTG2502','2025-06-29 16:41:18','2025-06-29 17:03:32'),
('BRG202506290003','Cat Choize Pouch 75gr Makanan Kucing Basah Tuna',199,'1751216565_1024be5364ace519d2d4.jpg',4000,7000,'Pcs','KTG2502','2025-06-29 17:02:45','2025-06-29 18:27:56');

/*Table structure for table `barangmasuk` */

DROP TABLE IF EXISTS `barangmasuk`;

CREATE TABLE `barangmasuk` (
  `kdmasuk` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tglmasuk` date NOT NULL,
  `kdspl` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `grandtotal` double NOT NULL DEFAULT '0',
  `keterangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=pending, 1=selesai',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdmasuk`),
  KEY `barangmasuk_kdspl_foreign` (`kdspl`),
  CONSTRAINT `barangmasuk_kdspl_foreign` FOREIGN KEY (`kdspl`) REFERENCES `supplier` (`kdspl`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `barangmasuk` */

insert  into `barangmasuk`(`kdmasuk`,`tglmasuk`,`kdspl`,`grandtotal`,`keterangan`,`status`,`created_at`,`updated_at`) values 
('BM202506290001','2025-06-29','SPL250629001',2000000,'Barang Masuk',1,'2025-06-29 16:41:53','2025-06-29 16:41:53'),
('BM202506290002','2025-06-29','SPL250629001',800000,'Beli Makanan Basah',1,'2025-06-29 17:04:35','2025-06-29 17:04:35');

/*Table structure for table `detailbarangmasuk` */

DROP TABLE IF EXISTS `detailbarangmasuk`;

CREATE TABLE `detailbarangmasuk` (
  `iddetail` int unsigned NOT NULL AUTO_INCREMENT,
  `detailkdmasuk` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `detailkdbarang` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `harga` double NOT NULL DEFAULT '0',
  `totalharga` double NOT NULL DEFAULT '0',
  `namabarang` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `detailbarangmasuk_detailkdmasuk_foreign` (`detailkdmasuk`),
  KEY `detailbarangmasuk_detailkdbarang_foreign` (`detailkdbarang`),
  CONSTRAINT `detailbarangmasuk_detailkdbarang_foreign` FOREIGN KEY (`detailkdbarang`) REFERENCES `barang` (`kdbarang`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detailbarangmasuk_detailkdmasuk_foreign` FOREIGN KEY (`detailkdmasuk`) REFERENCES `barangmasuk` (`kdmasuk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detailbarangmasuk` */

insert  into `detailbarangmasuk`(`iddetail`,`detailkdmasuk`,`detailkdbarang`,`jumlah`,`harga`,`totalharga`,`namabarang`,`created_at`,`updated_at`) values 
(1,'BM202506290001','BRG202506290001',100,5000,500000,'Kalung Kucing','2025-06-29 16:41:53','2025-06-29 16:41:53'),
(2,'BM202506290001','BRG202506290002',100,15000,1500000,'Cat Choize','2025-06-29 16:41:53','2025-06-29 16:41:53'),
(3,'BM202506290002','BRG202506290003',200,4000,800000,'Cat Choize Pouch 75gr Makanan Kucing Basah Tuna','2025-06-29 17:04:35','2025-06-29 17:04:35');

/*Table structure for table `detailpenitipan` */

DROP TABLE IF EXISTS `detailpenitipan`;

CREATE TABLE `detailpenitipan` (
  `iddetail` int unsigned NOT NULL AUTO_INCREMENT,
  `kdpenitipan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `idhewan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `kdfasilitas` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `harga` double NOT NULL DEFAULT '0',
  `totalharga` double NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `detailpenitipan_kdpenitipan_foreign` (`kdpenitipan`),
  KEY `detailpenitipan_idhewan_foreign` (`idhewan`),
  KEY `detailpenitipan_kdfasilitas_foreign` (`kdfasilitas`),
  CONSTRAINT `detailpenitipan_idhewan_foreign` FOREIGN KEY (`idhewan`) REFERENCES `hewan` (`idhewan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detailpenitipan_kdfasilitas_foreign` FOREIGN KEY (`kdfasilitas`) REFERENCES `fasilitas` (`kdfasilitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detailpenitipan_kdpenitipan_foreign` FOREIGN KEY (`kdpenitipan`) REFERENCES `penitipan` (`kdpenitipan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detailpenitipan` */

insert  into `detailpenitipan`(`iddetail`,`kdpenitipan`,`idhewan`,`kdfasilitas`,`jumlah`,`harga`,`totalharga`,`created_at`,`updated_at`) values 
(1,'PT202506290001','HWN202506290004','FS202506290001',1,700000,700000,'2025-06-29 19:13:17','2025-06-29 19:13:17'),
(2,'PT202506290001','HWN202506290004','FS202506290002',1,400000,400000,'2025-06-29 19:13:17','2025-06-29 19:13:17'),
(3,'PT202506290002','HWN202506290008','FS202506290002',1,400000,400000,'2025-06-29 19:13:47','2025-06-29 19:13:47');

/*Table structure for table `detailpenjualan` */

DROP TABLE IF EXISTS `detailpenjualan`;

CREATE TABLE `detailpenjualan` (
  `iddetail` int unsigned NOT NULL AUTO_INCREMENT,
  `detailkdpenjualan` char(30) COLLATE utf8mb4_general_ci NOT NULL,
  `detailkdbarang` char(30) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `harga` double NOT NULL DEFAULT '0',
  `totalharga` double NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `detailpenjualan_detailkdpenjualan_foreign` (`detailkdpenjualan`),
  KEY `detailpenjualan_detailkdbarang_foreign` (`detailkdbarang`),
  CONSTRAINT `detailpenjualan_detailkdbarang_foreign` FOREIGN KEY (`detailkdbarang`) REFERENCES `barang` (`kdbarang`) ON DELETE CASCADE,
  CONSTRAINT `detailpenjualan_detailkdpenjualan_foreign` FOREIGN KEY (`detailkdpenjualan`) REFERENCES `penjualan` (`kdpenjualan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detailpenjualan` */

insert  into `detailpenjualan`(`iddetail`,`detailkdpenjualan`,`detailkdbarang`,`jumlah`,`harga`,`totalharga`,`created_at`,`updated_at`) values 
(1,'PJ202506290001','BRG202506290001',1,10000,10000,'2025-06-29 16:42:22','2025-06-29 16:42:22'),
(2,'PJ202506290001','BRG202506290002',1,25000,25000,'2025-06-29 16:42:22','2025-06-29 16:42:22'),
(3,'PJ202506290002','BRG202506290003',1,7000,7000,'2025-06-29 18:27:56','2025-06-29 18:27:56');

/*Table structure for table `detailperawatan` */

DROP TABLE IF EXISTS `detailperawatan`;

CREATE TABLE `detailperawatan` (
  `iddetail` int unsigned NOT NULL AUTO_INCREMENT,
  `detailkdperawatan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `detailkdfasilitas` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `harga` double NOT NULL DEFAULT '0',
  `totalharga` double NOT NULL DEFAULT '0',
  `statusdetail` int NOT NULL DEFAULT '0' COMMENT '0: Menunggu, 1: Dalam Proses, 2: Selesai',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `detailperawatan_detailkdperawatan_foreign` (`detailkdperawatan`),
  KEY `detailperawatan_detailkdfasilitas_foreign` (`detailkdfasilitas`),
  CONSTRAINT `detailperawatan_detailkdfasilitas_foreign` FOREIGN KEY (`detailkdfasilitas`) REFERENCES `fasilitas` (`kdfasilitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detailperawatan_detailkdperawatan_foreign` FOREIGN KEY (`detailkdperawatan`) REFERENCES `perawatan` (`kdperawatan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detailperawatan` */

insert  into `detailperawatan`(`iddetail`,`detailkdperawatan`,`detailkdfasilitas`,`jumlah`,`harga`,`totalharga`,`statusdetail`,`created_at`,`updated_at`) values 
(6,'PRW202506290001','FS202506290001',1,700000,700000,0,NULL,NULL),
(7,'PRW202506290002','FS202506290001',1,700000,700000,0,NULL,NULL),
(8,'PRW202506300001','FS202506290001',1,700000,700000,0,NULL,NULL);

/*Table structure for table `fasilitas` */

DROP TABLE IF EXISTS `fasilitas`;

CREATE TABLE `fasilitas` (
  `kdfasilitas` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `namafasilitas` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double NOT NULL DEFAULT '0',
  `satuan` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Hari',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdfasilitas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `fasilitas` */

insert  into `fasilitas`(`kdfasilitas`,`namafasilitas`,`kategori`,`harga`,`satuan`,`keterangan`,`created_at`,`updated_at`) values 
('FS202506290001','Vitamin Bulanan','Medis',700000,'Kali','tes','2025-06-29 19:11:42','2025-06-29 19:11:42'),
('FS202506290002','Kandang','Kandang',400000,'Hari','tes','2025-06-29 19:11:58','2025-06-29 19:11:58');

/*Table structure for table `hewan` */

DROP TABLE IF EXISTS `hewan`;

CREATE TABLE `hewan` (
  `idhewan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `namahewan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Jenis hewan: Domestic, Campuran, Persian, Maine Coon, Siamese, British Shorthair, Ragdoll, Bengal, Sphynx, Scottish Fold, Angora, Himalayan',
  `umur` int DEFAULT NULL,
  `satuan_umur` enum('tahun','bulan') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tahun' COMMENT 'Satuan umur hewan: tahun atau bulan',
  `jenkel` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'L=Laki-laki, P=Perempuan',
  `foto` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idpelanggan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idhewan`),
  KEY `hewan_idpelanggan_foreign` (`idpelanggan`),
  CONSTRAINT `hewan_idpelanggan_foreign` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `hewan` */

insert  into `hewan`(`idhewan`,`namahewan`,`jenis`,`umur`,`satuan_umur`,`jenkel`,`foto`,`idpelanggan`,`created_at`,`updated_at`) values 
('HWN202506290001','Rocky','2',10,'tahun','L','default_dog.jpg','PLG202506290004','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290002','Max','2',1,'tahun','L','default_dog.jpg','PLG202506290004','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290003','Sadie','2',8,'tahun','P','default_dog.jpg','PLG202506290003','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290004','Lucy','1',9,'tahun','L','default_cat.jpg','PLG202506290001','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290005','Charlie','1',7,'tahun','L','default_cat.jpg','PLG202506290004','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290006','Leo','1',1,'tahun','L','default_cat.jpg','PLG202506290002','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290007','Charlie','1',6,'tahun','P','default_cat.jpg','PLG202506290004','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290008','Sadie','2',8,'tahun','L','default_dog.jpg','PLG202506290002','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290009','Molly','2',6,'tahun','L','default_dog.jpg','PLG202506290004','2025-06-29 16:35:28','2025-06-29 16:35:28'),
('HWN202506290010','Buddy','2',2,'tahun','L','default_dog.jpg','PLG202506290005','2025-06-29 16:35:28','2025-06-29 16:35:28');

/*Table structure for table `kategori` */

DROP TABLE IF EXISTS `kategori`;

CREATE TABLE `kategori` (
  `kdkategori` char(7) COLLATE utf8mb4_general_ci NOT NULL,
  `namakategori` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdkategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `kategori` */

insert  into `kategori`(`kdkategori`,`namakategori`,`created_at`,`updated_at`) values 
('KTG2501','Aksesoris','2025-06-29 16:39:47','2025-06-29 16:39:47'),
('KTG2502','Makanan','2025-06-29 16:39:55','2025-06-29 16:39:55');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(1,'2023-08-01-000001','App\\Database\\Migrations\\CreateUsersTable','default','App',1751214867,1),
(2,'2024-05-01-000001','App\\Database\\Migrations\\CreatePelangganTable','default','App',1751214868,1),
(3,'2024-06-14-100000','App\\Database\\Migrations\\CreatePenjualanTable','default','App',1751214868,1),
(4,'2025-06-12-045018','App\\Database\\Migrations\\CreateHewanTable','default','App',1751214868,1),
(5,'2025-06-12-090000','App\\Database\\Migrations\\CreateKategoriTable','default','App',1751214868,1),
(6,'2025-06-12-100000','App\\Database\\Migrations\\CreateBarangTable','default','App',1751214868,1),
(7,'2025-06-12-110000','App\\Database\\Migrations\\CreateSupplierTable','default','App',1751214868,1),
(8,'2025-06-12-120000','App\\Database\\Migrations\\CreateBarangMasukTable','default','App',1751214868,1),
(9,'2025-06-12-130000','App\\Database\\Migrations\\CreateDetailBarangMasukTable','default','App',1751214868,1),
(10,'2025-06-14-020314','App\\Database\\Migrations\\CreateFasilitasTable','default','App',1751214868,1),
(11,'2025-06-14-020319','App\\Database\\Migrations\\CreatePenitipanTable','default','App',1751214868,1),
(12,'2025-06-14-020326','App\\Database\\Migrations\\CreateDetailPenitipanTable','default','App',1751214868,1),
(13,'2025-06-14-042608','App\\Database\\Migrations\\AddDendaFieldsToPenitipan','default','App',1751214868,1),
(14,'2025-06-14-064045','App\\Database\\Migrations\\CreatePerawatanTable','default','App',1751214868,1),
(15,'2025-06-14-100001','App\\Database\\Migrations\\CreateDetailPenjualanTable','default','App',1751214868,1),
(16,'2025-06-14-200000','App\\Database\\Migrations\\AddSatuanToBarang','default','App',1751216439,2);

/*Table structure for table `pelanggan` */

DROP TABLE IF EXISTS `pelanggan`;

CREATE TABLE `pelanggan` (
  `idpelanggan` char(30) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jenkel` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'L=Laki-laki, P=Perempuan',
  `alamat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nohp` char(15) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idpelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pelanggan` */

insert  into `pelanggan`(`idpelanggan`,`nama`,`jenkel`,`alamat`,`nohp`,`created_at`,`updated_at`,`deleted_at`) values 
('PLG202506290001','Budi Santoso','L','Jl. Merdeka No. 123, Jakarta Pusat','081234567890','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290002','Siti Nurhaliza','P','Jl. Pahlawan No. 45, Bandung','082345678901','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290003','Ahmad Dhani','L','Jl. Sudirman No. 78, Surabaya','083456789012','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290004','Dewi Lestari','P','Jl. Gatot Subroto No. 56, Yogyakarta','084567890123','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290005','Rudi Hartono','L','Jl. Ahmad Yani No. 34, Semarang','085678901234','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290006','Ratna Sari','P','Jl. Diponegoro No. 67, Malang','086789012345','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290007','Andi Wijaya','L','Jl. Veteran No. 89, Makassar','087890123456','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290008','Rina Marlina','P','Jl. Imam Bonjol No. 12, Medan','088901234567','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290009','Doni Kusuma','L','Jl. Thamrin No. 23, Denpasar','089012345678','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
('PLG202506290010','Maya Putri','P','Jl. Gajah Mada No. 45, Palembang','081234567891','2025-06-29 16:35:28','2025-06-29 16:35:28',NULL);

/*Table structure for table `penitipan` */

DROP TABLE IF EXISTS `penitipan`;

CREATE TABLE `penitipan` (
  `kdpenitipan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tglpenitipan` date NOT NULL,
  `tglselesai` date NOT NULL,
  `tglpenjemputan` date DEFAULT NULL,
  `idpelanggan` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `durasi` int NOT NULL DEFAULT '1',
  `grandtotal` double NOT NULL DEFAULT '0',
  `total_biaya_dengan_denda` double DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Pending, 1=Dalam Penitipan, 2=Selesai',
  `is_terlambat` tinyint(1) DEFAULT '0' COMMENT '0=Tidak Terlambat, 1=Terlambat',
  `jumlah_hari_terlambat` int DEFAULT '0',
  `biaya_denda` double DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdpenitipan`),
  KEY `penitipan_idpelanggan_foreign` (`idpelanggan`),
  CONSTRAINT `penitipan_idpelanggan_foreign` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `penitipan` */

insert  into `penitipan`(`kdpenitipan`,`tglpenitipan`,`tglselesai`,`tglpenjemputan`,`idpelanggan`,`durasi`,`grandtotal`,`total_biaya_dengan_denda`,`status`,`is_terlambat`,`jumlah_hari_terlambat`,`biaya_denda`,`keterangan`,`created_at`,`updated_at`) values 
('PT202506290001','2025-06-29','2025-06-30',NULL,'PLG202506290001',1,1100000,0,1,0,0,0,'','2025-06-29 19:13:17','2025-06-29 19:13:17'),
('PT202506290002','2025-06-29','2025-06-30','2025-06-30','PLG202506290002',1,400000,400000,2,0,0,0,'','2025-06-29 19:13:47','2025-06-29 19:14:34');

/*Table structure for table `penjualan` */

DROP TABLE IF EXISTS `penjualan`;

CREATE TABLE `penjualan` (
  `kdpenjualan` char(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tglpenjualan` date NOT NULL,
  `idpelanggan` char(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `grandtotal` double NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=pending, 1=selesai',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdpenjualan`),
  KEY `penjualan_idpelanggan_foreign` (`idpelanggan`),
  CONSTRAINT `penjualan_idpelanggan_foreign` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `penjualan` */

insert  into `penjualan`(`kdpenjualan`,`tglpenjualan`,`idpelanggan`,`grandtotal`,`status`,`created_at`,`updated_at`) values 
('PJ202506290001','2025-06-29','PLG202506290001',35000,1,'2025-06-29 16:42:22','2025-06-29 16:42:22'),
('PJ202506290002','2025-06-29',NULL,7000,1,'2025-06-29 18:27:56','2025-06-29 18:27:56');

/*Table structure for table `perawatan` */

DROP TABLE IF EXISTS `perawatan`;

CREATE TABLE `perawatan` (
  `kdperawatan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tglperawatan` date NOT NULL,
  `idpelanggan` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idhewan` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `grandtotal` double NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0' COMMENT '0: Pending, 1: Dalam Proses, 2: Selesai',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdperawatan`),
  KEY `perawatan_idpelanggan_foreign` (`idpelanggan`),
  CONSTRAINT `perawatan_idpelanggan_foreign` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `perawatan` */

insert  into `perawatan`(`kdperawatan`,`tglperawatan`,`idpelanggan`,`idhewan`,`grandtotal`,`status`,`keterangan`,`created_at`,`updated_at`) values 
('PRW202506290001','2025-06-29','PLG202506290001','HWN202506290004',700000,2,'','2025-06-29 19:57:25','2025-06-29 19:57:25'),
('PRW202506290002','2025-06-29','PLG202506290002','HWN202506290008',700000,2,'','2025-06-29 19:57:43','2025-06-29 19:57:43'),
('PRW202506300001','2025-06-30','PLG202506290002','HWN202506290006',700000,2,'','2025-06-30 03:00:33','2025-06-30 03:00:33');

/*Table structure for table `supplier` */

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `kdspl` char(30) COLLATE utf8mb4_general_ci NOT NULL,
  `namaspl` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nohp` char(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `email` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdspl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `supplier` */

insert  into `supplier`(`kdspl`,`namaspl`,`nohp`,`alamat`,`email`,`created_at`,`updated_at`) values 
('SPL250629001','PT.CatChoize','083182423488','Padang','catchoize@pingaja.site','2025-06-29 16:37:46','2025-06-29 16:37:46');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'admin, user, dll',
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`name`,`role`,`status`,`last_login`,`remember_token`,`created_at`,`updated_at`,`deleted_at`) values 
(6,'admin','admin@admin.com','$2y$10$QM/qWJZx.dSftIiKJZ.p8eptUFCsEEf.kr3bf/vqUMOKGK70M5T6G','Administrator','admin','active','2025-06-29 18:10:14','1041fe88d6d942c200a04d8f6eb9c2a3b05902aa7a0fbeb7cbd78d3242b61553','2025-06-29 16:35:28','2025-06-29 18:10:14',NULL),
(7,'manager','manager@example.com','$2y$10$4einGv1YSUElxFrpzQ5yXusLZCEpIMezemVQLH.TH4u0Quzj0v/na','Manager User','manager','active',NULL,NULL,'2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
(8,'user','user@example.com','$2y$10$eFjqDcmpuedbdV925yqKyeowbMkOCMqVumBqIeSQMcoA/koM9mODS','Regular User','user','active',NULL,NULL,'2025-06-29 16:35:28','2025-06-29 16:35:28',NULL),
(9,'inactive','inactive@example.com','$2y$10$3OpuXniF8bk7X/nMqHt1OeAHmQoBzxGkgltKSnxGP.OTDHzA0FVqK','Inactive User','user','inactive',NULL,NULL,'2025-06-29 16:35:28','2025-06-29 16:35:28',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
