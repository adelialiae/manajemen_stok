-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2025 at 10:06 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlineshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', '$2y$10$S/rkfvHUnYJO0xPwm3uOYOmUAPdSy5dP4n.Qs1y5Eoy9DLya5TU2u'),
('admin2', '$2y$10$g5u/cG3s35hz105zPgcqr.vOX82p05aqamOe/cK0m777MTkc.JLya');

-- --------------------------------------------------------

--
-- Table structure for table `bahan_baku`
--

CREATE TABLE `bahan_baku` (
  `id_bahan` int NOT NULL,
  `nama_bahan` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga` int NOT NULL,
  `stok` int DEFAULT '0',
  `id_supplier` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bahan_baku`
--

INSERT INTO `bahan_baku` (`id_bahan`, `nama_bahan`, `gambar`, `harga`, `stok`, `id_supplier`) VALUES
(1, 'Fiber Cream', 'bahan_683f36f944588.jpg', 12000, 388, 1),
(2, 'Daun Suji', 'bahan_683f37f614780.jpg', 30000, 500, 1),
(3, 'Botol 100ml', 'bahan_683f3d345d6c2.webp', 5000, 501, 2),
(5, 'Fiber Cream Ellenka 1Kg', 'bahan_68539c98e437c.jpg', 100000, 29, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namaLengkap` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `paypalID` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`username`, `userID`, `password`, `namaLengkap`, `email`, `dob`, `gender`, `alamat`, `kota`, `contact`, `paypalID`) VALUES
('adeleealea', 'USR-20250420103212', '$2y$10$a6SjlVf1ffIgCTnGeap5EukBcuLtYP714m471mzTANJdnApGgBWpa', 'Adelia Tiwiw', 'adelia.utri28@gmail.com', '2004-07-18', 'Female', 'JL.CEMPAKA RT 03/RW 01, DS. JATIREJO,KEC. LOCERET,KAB. NGANJUK', 'Surabaya', '085708809957', '11144556'),
('adeliaa', 'USR - 20250420113851', '$2y$10$.QAEVL7jct/NZSs1qStYj.gRW4ixH07c.zWuQw9uApnfKwUOMESUS', 'Adeliaa putri', 'adelia.utri28@gmail.com', '2025-04-20', 'Female', 'Jl. Rungkut Asri Timur', 'Surabaya', '02245671', '4459872'),
('adeliap', 'USR - 20250603160751+2025', '$2y$10$VoW9b0mmNwgU1ETa/M/RheMg3PI5F80inxqP4Sa0FcwKGAf/nNJlS', 'Adelia Putri', 'adelia.utri28@gmail.com', '2025-06-03', 'Female', 'Stuttgarter Straße 26', 'Surabaya', '022336664', '2234'),
('pina', 'USR - 20250619071715+2025', '$2y$10$AjAZeYN84o6D8W4LF9JBZOcE/H9JiWyWBSb1OlWP16Im9tv5IqYEG', 'alfina', 'alfinamazida70@gmail.com', '2025-06-19', 'Female', 'Wonokromo', 'Surabaya', '081577459635', '12345678'),
('yisti', 'USR - 20250421074111+2025', '$2y$10$DIe8MJ8Kn8l4EdyTAV7fBONbo2k4SxeBK9pnIgnFTRCdh.7R9cOAq', 'Yisti Via', 'adelia.utri28@gmail.com', '2006-07-18', 'Female', 'Jl. Rungkut Madya ', 'Surabaya', '0224533698', '12336547');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi_pembelian`
--

CREATE TABLE `detail_transaksi_pembelian` (
  `idDetail` int NOT NULL,
  `idTransaksi` varchar(50) DEFAULT NULL,
  `id_bahan` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `harga` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_transaksi_pembelian`
--

INSERT INTO `detail_transaksi_pembelian` (`idDetail`, `idTransaksi`, `id_bahan`, `qty`, `harga`) VALUES
(1, 'TRX684094da4a582', 3, 1, 5000),
(2, 'TRX6840dd29e94f6', 1, 1, 12000),
(3, 'TRX68410fdf08500', 2, 1, 30000),
(4, 'TRX6841110c21e3a', 3, 1, 5000),
(5, 'TRX684114fe1fc1c', 2, 1, 30000),
(6, 'TRX68430e3550d9e', 1, 2, 24000),
(7, 'TRX68431d645139c', 2, 4, 120000),
(8, 'TRX68496897e4968', 1, 1000, 12000000),
(9, 'TRX6849b2b464efc', 3, 10, 50000),
(10, 'TRX6849b84c16bb2', 1, 10, 120000),
(11, 'TRX684a2f7a5890a', 3, 10, 50000),
(12, 'TRX684a5dea756f9', 2, 2, 60000),
(13, 'TRX68539fa6443b5', 5, 1, 100000),
(14, 'TRX6853a03471ddc', 3, 10, 50000),
(15, 'TRX6853a12a5d496', 2, 1, 30000),
(16, 'TRX6853a12a5d496', 1, 1, 12000),
(17, 'TRX6853a23c970cb', 3, 1, 5000),
(18, 'TRX6853a24c200af', 2, 1, 30000),
(19, 'TRX6853c43a60dcf', 3, 500, 2500000),
(20, 'TRX6853c5fb1866d', 1, 1, 12000);

-- --------------------------------------------------------

--
-- Table structure for table `inventorystokbahan`
--

CREATE TABLE `inventorystokbahan` (
  `idInventory` int NOT NULL,
  `id_bahan` int NOT NULL,
  `namaBahan` varchar(100) NOT NULL,
  `stokSisa` int NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `tanggalUpdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventorystokbahan`
--

INSERT INTO `inventorystokbahan` (`idInventory`, `id_bahan`, `namaBahan`, `stokSisa`, `satuan`, `tanggalUpdate`) VALUES
(4, 3, 'Botol 100ml', 15, '', '2025-06-19 08:05:23'),
(5, 1, 'Fiber Cream', 10, '', '2025-06-11 17:28:18'),
(6, 5, 'Fiber Cream Ellenka 1Kg', 1, '', '2025-06-19 05:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `idKategori` int NOT NULL,
  `namaKategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `idKeranjang` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idProduk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int NOT NULL,
  `hargaProduk` int NOT NULL,
  `status` enum('Belum Dibayar','Dibayar','Dibatalkan') COLLATE utf8mb4_general_ci NOT NULL,
  `idTransaksi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`idKeranjang`, `username`, `idProduk`, `jumlah`, `hargaProduk`, `status`, `idTransaksi`) VALUES
(204, 'adeliap', 'PRD-1749269312', 9, 90000, 'Dibayar', 'TRS-20250607053817'),
(206, 'adeliap', 'PRD-1749269673', 1, 10000, 'Dibayar', 'TRS-20250611165734'),
(207, 'adeliap', 'PRD-1749269359', 2, 20000, 'Dibayar', 'TRS-20250611165734'),
(208, 'adeliap', 'PRD-1749269399', 1, 10000, 'Dibayar', 'TRS-20250611165817'),
(209, 'pina', 'PRD-1749269312', 1, 10000, 'Dibayar', 'TRS-20250619074802');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang_pembelian`
--

CREATE TABLE `keranjang_pembelian` (
  `idKeranjang` int NOT NULL,
  `idTransaksi` varchar(20) DEFAULT NULL,
  `id_bahan` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `keranjang_pembelian`
--

INSERT INTO `keranjang_pembelian` (`idKeranjang`, `idTransaksi`, `id_bahan`, `jumlah`, `harga`) VALUES
(5, 'TRX68404d4badcd0', 1, 2, 24000),
(6, 'TRX68405090b5dcf', 2, 1, 30000),
(7, 'TRX68408770c33cd', 3, 2, 10000),
(8, 'TRX68408770c33cd', 2, 1, 30000),
(9, 'TRX6840891b64ec3', 2, 1, 30000),
(10, 'TRX68408c1fb8901', 3, 1, 5000),
(11, 'TRX68408d431ca6b', 2, 1, 30000),
(12, 'TRX684094da4a582', 3, 1, 5000),
(13, 'TRX6840dd29e94f6', 1, 1, 12000),
(14, 'TRX68410fdf08500', 2, 1, 30000),
(15, 'TRX6841110c21e3a', 3, 1, 5000),
(16, 'TRX684114fe1fc1c', 2, 1, 30000),
(18, 'TRX68430e3550d9e', 1, 2, 24000),
(20, 'TRX68431d645139c', 2, 4, 120000),
(21, 'TRX68496897e4968', 1, 1000, 12000000),
(22, 'TRX6849b2b464efc', 3, 10, 50000),
(23, 'TRX6849b84c16bb2', 1, 10, 120000),
(24, 'TRX684a2f7a5890a', 3, 10, 50000),
(25, 'TRX684a5dea756f9', 2, 2, 60000),
(26, 'TRX68539fa6443b5', 5, 1, 100000),
(27, 'TRX6853a03471ddc', 3, 10, 50000),
(28, 'TRX6853a12a5d496', 2, 1, 30000),
(29, 'TRX6853a12a5d496', 1, 1, 12000),
(30, 'TRX6853a23c970cb', 3, 1, 5000),
(31, 'TRX6853a24c200af', 2, 1, 30000),
(32, 'TRX6853c43a60dcf', 3, 500, 2500000),
(33, 'TRX6853c5fb1866d', 1, 1, 12000);

-- --------------------------------------------------------

--
-- Table structure for table `log_pemakaian_bahan`
--

CREATE TABLE `log_pemakaian_bahan` (
  `idLog` int NOT NULL,
  `id_bahan` int NOT NULL,
  `jumlahDigunakan` int NOT NULL,
  `tanggalPemakaian` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_bahan_baku`
--

CREATE TABLE `pemesanan_bahan_baku` (
  `pemesanan_bahan_id` int NOT NULL,
  `id_supplier` int NOT NULL,
  `id_bahan` int NOT NULL,
  `qty` int NOT NULL,
  `harga_total` decimal(12,2) NOT NULL,
  `status_pemesanan` enum('pesan','dibayar','diterima','retur') DEFAULT 'pesan',
  `tanggal_pemesanan` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_terima` datetime DEFAULT NULL,
  `tanggal_retur` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permintaan_harian`
--

CREATE TABLE `permintaan_harian` (
  `id` int NOT NULL,
  `id_bahan` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permintaan_harian`
--

INSERT INTO `permintaan_harian` (`id`, `id_bahan`, `tanggal`, `jumlah`) VALUES
(1, 1, '2025-06-11', 500),
(2, 3, '2025-06-11', 5),
(3, 3, '2025-06-19', 500);

-- --------------------------------------------------------

--
-- Table structure for table `produkjadi`
--

CREATE TABLE `produkjadi` (
  `idProduk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namaProduk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `varianRasa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hargaProduk` int NOT NULL,
  `stokProduk` int NOT NULL,
  `gambarProduk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsiProduk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produkjadi`
--

INSERT INTO `produkjadi` (`idProduk`, `namaProduk`, `varianRasa`, `hargaProduk`, `stokProduk`, `gambarProduk`, `deskripsiProduk`) VALUES
('PRD-1749269312', 'Dawet Suji Taro', 'Taro', 10000, 49, 'taro.jpg', 'Dawet segar dengan cita rasa taro yang lembut dan creamy. Warna ungu khas taro memberikan tampilan cantik dan rasa manis yang pas.'),
('PRD-1749269359', 'Dawet Suji Coklat', 'Coklat', 10000, 50, 'dawet coklat.jpg', 'Nikmati dawet tradisional dengan sentuhan rasa coklat yang kaya. Cocok untuk kamu yang ingin sensasi manis legit dan aroma coklat yang menggoda.'),
('PRD-1749269399', 'Dawet Suji Strawberry', 'Strawberry', 10000, 20, 'strawberry.jpeg', 'Dawet suji yang segar dipadukan dengan rasa strawberry yang manis dan asam segar. Cocok dinikmati saat cuaca panas!'),
('PRD-1749269547', 'Dawet Suji Original', 'Original', 10000, 5, 'ori.jpg', 'Versi klasik dari dawet suji, dengan rasa gurih santan dan manis legit gula merah yang otentik. Selalu jadi pilihan favorit!'),
('PRD-1749269591', 'Dawet Suji Matcha', 'Matcha', 10000, 30, 'matcha.jpeg', 'Perpaduan unik antara tradisi dan modern. Rasa matcha yang earthy dan creamy menambah kenikmatan dalam setiap tegukan.'),
('PRD-1749269631', 'Dawet Suji Cappucino', 'Cappucino', 10000, 25, 'capucino.jpeg', 'Kombinasi dawet segar dan aroma cappucino yang khas. Cocok untuk kamu pecinta kopi yang ingin mencoba hal baru'),
('PRD-1749269673', 'Dawet Suji Gula Aren', 'Gula Aren', 10000, 23, 'aren.jpg', 'Manis legit khas gula aren asli, berpadu sempurna dengan dawet suji yang kenyal. Memberikan rasa tradisional yang otentik.');

-- --------------------------------------------------------

--
-- Table structure for table `rasio_bahan_produk`
--

CREATE TABLE `rasio_bahan_produk` (
  `idProduk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_bahan` int NOT NULL,
  `rasio_penggunaan` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rasio_bahan_produk`
--

INSERT INTO `rasio_bahan_produk` (`idProduk`, `id_bahan`, `rasio_penggunaan`) VALUES
('PRD-1749269312', 1, 0.01),
('PRD-1749269359', 2, 0.05),
('PRD-1749269399', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `retur_bahan_baku`
--

CREATE TABLE `retur_bahan_baku` (
  `retur_id` int NOT NULL,
  `pemesanan_bahan_id` int NOT NULL,
  `qty_retur` int NOT NULL,
  `status_retur` enum('pending','diterima','ditolak','dikirimkan') DEFAULT 'pending',
  `tanggal_retur` datetime DEFAULT CURRENT_TIMESTAMP,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_supplier` varchar(100) DEFAULT NULL,
  `kontak_supplier` varchar(100) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `username`, `password`, `nama_supplier`, `kontak_supplier`, `alamat`) VALUES
(1, 'suppfiba', '$2y$10$smThVJyWnf3yjgGBAGuuD.nexed0lYkoUZmFlfLPUAkjNN6QCqvpa', 'Fiba Cream', '08551223647', 'Surabaya'),
(2, 'packaging', '$2y$10$YoDldG4V4Khh/IK.sS0ZU.Yq02xXWfRw/1F6jE8LMkPmvPGQZUvEq', 'Indo Packaging', '085708809957', 'Sidoarjo');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_user`
--

CREATE TABLE `supplier_user` (
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `idTransaksi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggalTransaksi` date NOT NULL,
  `caraBayar` enum('Prepaid','Postpaid') COLLATE utf8mb4_general_ci NOT NULL,
  `bank` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `statusTransaksi` enum('Pending','Accepted','Rejected','Cancelled') COLLATE utf8mb4_general_ci NOT NULL,
  `totalHarga` int NOT NULL,
  `statusPengiriman` enum('Pending','Dalam Perjalanan','Terkirim','Dibatalkan') COLLATE utf8mb4_general_ci NOT NULL,
  `feedBack` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `aksi` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`idTransaksi`, `username`, `tanggalTransaksi`, `caraBayar`, `bank`, `statusTransaksi`, `totalHarga`, `statusPengiriman`, `feedBack`, `aksi`) VALUES
('TRS-20250607053817', 'adeliap', '2025-06-07', 'Postpaid', 'Bayar Ditempat', 'Accepted', 99900, 'Terkirim', '', 'menunggu'),
('TRS-20250611165734', 'adeliap', '2025-06-11', 'Prepaid', 'BCA', 'Rejected', 33300, 'Dibatalkan', '', 'menunggu'),
('TRS-20250611165817', 'adeliap', '2025-06-11', 'Prepaid', 'BNI', 'Rejected', 11100, 'Dibatalkan', '', 'menunggu'),
('TRS-20250619074802', 'pina', '2025-06-19', 'Postpaid', 'Bayar Ditempat', 'Pending', 11100, 'Pending', '', 'menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_pembelian`
--

CREATE TABLE `transaksi_pembelian` (
  `idTransaksi` varchar(20) NOT NULL,
  `id_supplier` int DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statusTransaksi` varchar(50) DEFAULT 'menunggu supplier',
  `totalHarga` double DEFAULT NULL,
  `caraBayar` enum('postpaid','prepaid') DEFAULT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `aksi` varchar(20) DEFAULT 'menunggu supplier',
  `statusSupplier` enum('menunggu','menyetujui','menolak') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_pembelian`
--

INSERT INTO `transaksi_pembelian` (`idTransaksi`, `id_supplier`, `tanggal`, `statusTransaksi`, `totalHarga`, `caraBayar`, `bank`, `aksi`, `statusSupplier`) VALUES
('TRX68408d431ca6b', 1, '2025-06-05 01:15:31', 'menunggu supplier', 30000, 'postpaid', '', 'menunggu supplier', 'menunggu'),
('TRX684094da4a582', 2, '2025-06-05 01:47:54', 'diterima', 5000, 'postpaid', '', 'menunggu supplier', 'menunggu'),
('TRX6840dd29e94f6', 1, '2025-06-05 06:56:25', 'diterima supplier', 12000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX68410fdf08500', 1, '2025-06-05 10:32:47', 'diterima', 30000, 'postpaid', '', 'menunggu supplier', 'menunggu'),
('TRX6841110c21e3a', 2, '2025-06-05 10:37:48', 'diterima', 5000, 'postpaid', '', 'menunggu supplier', 'menunggu'),
('TRX684114fe1fc1c', 1, '2025-06-05 10:54:38', 'diterima', 30000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX68430e3550d9e', 1, '2025-06-06 22:50:13', 'diterima', 24000, 'prepaid', 'BCA', 'menunggu supplier', 'menyetujui'),
('TRX68431d645139c', 1, '2025-06-06 23:55:00', 'retur disetujui', 120000, 'postpaid', '', 'menunggu supplier', 'menunggu'),
('TRX68496897e4968', 1, '2025-06-11 18:29:27', 'diterima', 12000000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX6849b2b464efc', 2, '2025-06-11 23:45:40', 'diterima', 50000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX6849b84c16bb2', 1, '2025-06-12 00:09:32', 'diterima', 120000, 'prepaid', 'BCA', 'menunggu supplier', 'menyetujui'),
('TRX684a2f7a5890a', 2, '2025-06-12 08:38:02', 'diterima', 50000, 'prepaid', 'BCA', 'menunggu supplier', 'menyetujui'),
('TRX684a5dea756f9', 1, '2025-06-12 11:56:10', 'ditolak supplier', 60000, 'postpaid', '', 'menunggu supplier', 'menolak'),
('TRX68539fa6443b5', 1, '2025-06-19 12:27:02', 'diterima', 100000, 'prepaid', 'BCA', 'menunggu supplier', 'menyetujui'),
('TRX6853a03471ddc', 2, '2025-06-19 12:29:24', 'diterima supplier', 50000, 'prepaid', 'BCA', 'menunggu supplier', 'menyetujui'),
('TRX6853a12a5d496', 1, '2025-06-19 12:33:30', 'retur_diterima', 42000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX6853a23c970cb', 2, '2025-06-19 12:38:04', 'diterima supplier', 5000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX6853a24c200af', 1, '2025-06-19 12:38:20', 'diterima supplier', 30000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX6853c43a60dcf', 2, '2025-06-19 15:03:06', 'diterima', 2500000, 'postpaid', '', 'menunggu supplier', 'menyetujui'),
('TRX6853c5fb1866d', 1, '2025-06-19 15:10:35', 'retur', 12000, 'prepaid', 'Mandiri', 'menunggu supplier', 'menyetujui');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `bahan_baku`
--
ALTER TABLE `bahan_baku`
  ADD PRIMARY KEY (`id_bahan`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `detail_transaksi_pembelian`
--
ALTER TABLE `detail_transaksi_pembelian`
  ADD PRIMARY KEY (`idDetail`),
  ADD KEY `idTransaksi` (`idTransaksi`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `inventorystokbahan`
--
ALTER TABLE `inventorystokbahan`
  ADD PRIMARY KEY (`idInventory`),
  ADD KEY `fk_bahan` (`id_bahan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`idKategori`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`idKeranjang`),
  ADD KEY `username` (`username`,`idProduk`),
  ADD KEY `idProduk` (`idProduk`);

--
-- Indexes for table `keranjang_pembelian`
--
ALTER TABLE `keranjang_pembelian`
  ADD PRIMARY KEY (`idKeranjang`),
  ADD KEY `idTransaksi` (`idTransaksi`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `log_pemakaian_bahan`
--
ALTER TABLE `log_pemakaian_bahan`
  ADD PRIMARY KEY (`idLog`),
  ADD KEY `fk_bahanBaku` (`id_bahan`);

--
-- Indexes for table `pemesanan_bahan_baku`
--
ALTER TABLE `pemesanan_bahan_baku`
  ADD PRIMARY KEY (`pemesanan_bahan_id`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `permintaan_harian`
--
ALTER TABLE `permintaan_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `produkjadi`
--
ALTER TABLE `produkjadi`
  ADD PRIMARY KEY (`idProduk`);

--
-- Indexes for table `rasio_bahan_produk`
--
ALTER TABLE `rasio_bahan_produk`
  ADD PRIMARY KEY (`idProduk`,`id_bahan`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `retur_bahan_baku`
--
ALTER TABLE `retur_bahan_baku`
  ADD PRIMARY KEY (`retur_id`),
  ADD KEY `pemesanan_bahan_id` (`pemesanan_bahan_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `supplier_user`
--
ALTER TABLE `supplier_user`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`idTransaksi`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `transaksi_pembelian`
--
ALTER TABLE `transaksi_pembelian`
  ADD PRIMARY KEY (`idTransaksi`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahan_baku`
--
ALTER TABLE `bahan_baku`
  MODIFY `id_bahan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_transaksi_pembelian`
--
ALTER TABLE `detail_transaksi_pembelian`
  MODIFY `idDetail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `inventorystokbahan`
--
ALTER TABLE `inventorystokbahan`
  MODIFY `idInventory` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `idKategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `idKeranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `keranjang_pembelian`
--
ALTER TABLE `keranjang_pembelian`
  MODIFY `idKeranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `log_pemakaian_bahan`
--
ALTER TABLE `log_pemakaian_bahan`
  MODIFY `idLog` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_bahan_baku`
--
ALTER TABLE `pemesanan_bahan_baku`
  MODIFY `pemesanan_bahan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permintaan_harian`
--
ALTER TABLE `permintaan_harian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `retur_bahan_baku`
--
ALTER TABLE `retur_bahan_baku`
  MODIFY `retur_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bahan_baku`
--
ALTER TABLE `bahan_baku`
  ADD CONSTRAINT `bahan_baku_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);

--
-- Constraints for table `detail_transaksi_pembelian`
--
ALTER TABLE `detail_transaksi_pembelian`
  ADD CONSTRAINT `detail_transaksi_pembelian_ibfk_1` FOREIGN KEY (`idTransaksi`) REFERENCES `transaksi_pembelian` (`idTransaksi`),
  ADD CONSTRAINT `detail_transaksi_pembelian_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_baku` (`id_bahan`);

--
-- Constraints for table `inventorystokbahan`
--
ALTER TABLE `inventorystokbahan`
  ADD CONSTRAINT `fk_bahan` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_baku` (`id_bahan`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customer` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `keranjang_pembelian`
--
ALTER TABLE `keranjang_pembelian`
  ADD CONSTRAINT `keranjang_pembelian_ibfk_1` FOREIGN KEY (`idTransaksi`) REFERENCES `transaksi_pembelian` (`idTransaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_pembelian_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_baku` (`id_bahan`);

--
-- Constraints for table `log_pemakaian_bahan`
--
ALTER TABLE `log_pemakaian_bahan`
  ADD CONSTRAINT `fk_bahanBaku` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_baku` (`id_bahan`);

--
-- Constraints for table `pemesanan_bahan_baku`
--
ALTER TABLE `pemesanan_bahan_baku`
  ADD CONSTRAINT `pemesanan_bahan_baku_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`),
  ADD CONSTRAINT `pemesanan_bahan_baku_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_baku` (`id_bahan`);

--
-- Constraints for table `permintaan_harian`
--
ALTER TABLE `permintaan_harian`
  ADD CONSTRAINT `permintaan_harian_ibfk_1` FOREIGN KEY (`id_bahan`) REFERENCES `inventorystokbahan` (`id_bahan`);

--
-- Constraints for table `rasio_bahan_produk`
--
ALTER TABLE `rasio_bahan_produk`
  ADD CONSTRAINT `fk_produkjadi_id` FOREIGN KEY (`idProduk`) REFERENCES `produkjadi` (`idProduk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rasio_bahan_produk_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `inventorystokbahan` (`id_bahan`);

--
-- Constraints for table `retur_bahan_baku`
--
ALTER TABLE `retur_bahan_baku`
  ADD CONSTRAINT `retur_bahan_baku_ibfk_1` FOREIGN KEY (`pemesanan_bahan_id`) REFERENCES `pemesanan_bahan_baku` (`pemesanan_bahan_id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`username`) REFERENCES `customer` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_pembelian`
--
ALTER TABLE `transaksi_pembelian`
  ADD CONSTRAINT `transaksi_pembelian_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
